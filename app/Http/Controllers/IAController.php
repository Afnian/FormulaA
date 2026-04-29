<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class IAController extends Controller
{
    // Tablas permitidas para consultas
    private array $tablasPermitidas = [
        'users',
        'pilotos',
        'escuderias',
        'temporadas',
        'inscripciones_piloto',
        'circuitos',
        'eventos',
        'resultados',
        'sistema_puntos',
        'noticias',
        'solicitudes_acceso',
    ];

    // Palabras clave peligrosas prohibidas
    private array $palabrasProhibidas = [
        'insert', 'update', 'delete', 'drop', 'truncate',
        'alter', 'create', 'replace', 'grant', 'revoke',
        'exec', 'execute', 'xp_', 'sp_', 'information_schema',
        'pg_', 'sys.', 'load_file', 'outfile', 'dumpfile',
    ];

    public function index()
    {
        return view('ia.consulta');
    }

    public function query(Request $request)
    {
        $request->validate([
            'pregunta' => 'required|string|max:500',
        ]);

        $pregunta = trim($request->pregunta);

        try {
            // 1. Generar SQL con Gemini
            $sql = $this->generarSQL($pregunta);

            if (!$sql) {
                return back()
                    ->withInput()
                    ->with('error', 'No se pudo generar una consulta SQL para tu pregunta. Intenta reformularla.');
            }

            // 2. Validar que sea SELECT
            if (!$this->esSelectValido($sql)) {
                return back()
                    ->withInput()
                    ->with('error', 'La consulta generada no es segura. Solo se permiten consultas de lectura (SELECT).');
            }

            // 3. Validar tablas permitidas
            if (!$this->tablasValidas($sql)) {
                return back()
                    ->withInput()
                    ->with('error', 'La consulta intenta acceder a tablas no permitidas.');
            }

            // 4. Ejecutar consulta
            $resultados = DB::select($sql);
            $columnas   = !empty($resultados) ? array_keys((array) $resultados[0]) : [];

            return view('ia.consulta', [
                'pregunta'   => $pregunta,
                'sql'        => $sql,
                'resultados' => $resultados,
                'columnas'   => $columnas,
            ]);

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Error al ejecutar la consulta: ' . $e->getMessage());
        }
    }

    private function generarSQL(string $pregunta): ?string
    {
        $esquema = $this->obtenerEsquema();
        $prompt  = $this->construirPrompt($pregunta, $esquema);

        $apiKey = config('services.ia.api_key');
        $model  = config('services.ia.model', 'gemini-2.0-flash');

        $response = Http::timeout(30)
            ->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature'     => 0.1,
                    'maxOutputTokens' => 512,
                ],
            ]);

        if (!$response->successful()) {
            throw new \Exception('Error al conectar con Gemini: ' . $response->status());
        }

        $data = $response->json();
        $texto = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

        if (!$texto) {
            return null;
        }

        return $this->limpiarSQL($texto);
    }

    private function construirPrompt(string $pregunta, string $esquema): string
    {
        return <<<PROMPT
Eres un experto en SQL para MySQL. Tu única función es generar consultas SQL SELECT.

ESQUEMA DE LA BASE DE DATOS:
{$esquema}

REGLAS ESTRICTAS:
1. Responde ÚNICAMENTE con la consulta SQL, sin explicaciones, sin markdown, sin bloques de código.
2. Solo puedes usar SELECT. NUNCA uses INSERT, UPDATE, DELETE, DROP, ALTER, CREATE, TRUNCATE.
3. Usa JOINs cuando necesites combinar tablas.
4. Limita los resultados a 50 filas máximo usando LIMIT 50.
5. Si la pregunta no tiene relación con la base de datos, responde exactamente: NO_APLICABLE
6. Usa nombres de columnas descriptivos con AS cuando sea necesario.
7. Para puntos totales usa: (pts_carrera + pts_pole + pts_vuelta_rap) AS puntos_totales

PREGUNTA DEL USUARIO:
{$pregunta}

SQL:
PROMPT;
    }

    private function obtenerEsquema(): string
    {
        return <<<ESQUEMA
- users: id, nombre, email, rol (admin/editor/piloto/espectador)
- pilotos: id, id_usuario (FK users), gamertag, nacionalidad
- escuderias: id, nombre, descripcion, logo_url
- temporadas: id, nombre, categoria (formula_a/formula_b), anio, activa
- inscripciones_piloto: id, id_piloto (FK), id_escuderia (FK), id_temporada (FK), tipo (oficial/reserva/academia)
- circuitos: id, nombre, pais, imagen_url, num_vueltas
- eventos: id, id_temporada (FK), id_circuito (FK), nombre, ronda, fecha, completado
- resultados: id, id_evento (FK), id_inscripcion (FK inscripciones_piloto), posicion, diferencia, pts_carrera, pts_vuelta_rap, pts_pole, dnf
- sistema_puntos: id, id_temporada (FK), posicion, puntos
- noticias: id, id_evento (FK nullable), id_autor (FK users), titulo, contenido, estado (borrador/publicada), publicado_en
- solicitudes_acceso: id, id_usuario (FK users), fecha_solicitud, estado (pendiente/aceptada/rechazada)
ESQUEMA;
    }

    private function limpiarSQL(string $texto): ?string
    {
        // Si la IA respondió que no aplica
        if (str_contains(strtoupper(trim($texto)), 'NO_APLICABLE')) {
            return null;
        }

        // Eliminar bloques markdown ```sql ... ```
        $texto = preg_replace('/```(?:sql)?\s*/i', '', $texto);
        $texto = preg_replace('/```/', '', $texto);

        // Eliminar saltos de línea extra y espacios
        $texto = trim($texto);

        // Quedarse solo con la primera sentencia (hasta el primer ;)
        if (str_contains($texto, ';')) {
            $texto = strstr($texto, ';', true);
        }

        return trim($texto) ?: null;
    }

    private function esSelectValido(string $sql): bool
    {
        $sqlLower = strtolower(trim($sql));

        // Debe empezar por SELECT
        if (!str_starts_with($sqlLower, 'select')) {
            return false;
        }

        // No puede contener palabras prohibidas
        foreach ($this->palabrasProhibidas as $palabra) {
            if (str_contains($sqlLower, $palabra)) {
                return false;
            }
        }

        return true;
    }

    private function tablasValidas(string $sql): bool
    {
        $sqlLower = strtolower($sql);

        // Extraer nombres de tablas tras FROM y JOIN
        preg_match_all('/(?:from|join)\s+`?(\w+)`?/i', $sqlLower, $matches);
        $tablasUsadas = $matches[1] ?? [];

        foreach ($tablasUsadas as $tabla) {
            if (!in_array($tabla, $this->tablasPermitidas)) {
                return false;
            }
        }

        return true;
    }
}