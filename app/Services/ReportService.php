<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use App\Models\InitialConfiguration;

class ReportService
{
    /**
     * Obtiene la zona horaria configurada del sistema
     */
    public static function getSystemTimezone(): string
    {
        $timezoneConfig = InitialConfiguration::getByKey('system_timezone');
        return $timezoneConfig ? $timezoneConfig->converted_value : config('app.timezone', 'America/Los_Angeles');
    }

    /**
     * Establece la zona horaria del sistema
     */
    public static function setSystemTimezone(string $timezone): void
    {
        InitialConfiguration::setByKey(
            'system_timezone',
            $timezone,
            'string',
            'Zona horaria configurada para el sistema'
        );
    }

    /**
     * Obtiene la fecha y hora actual en la zona horaria configurada
     */
    public static function getCurrentDateTime(): array
    {
        $timezone = self::getSystemTimezone();
        $now = Carbon::now($timezone);
        
        return [
            'date' => $now->format('Y-m-d'),
            'time' => $now->format('H:i'),
            'datetime' => $now->format('Y-m-d H:i:s'),
            'formatted_date' => $now->format('d/m/Y'),
            'formatted_datetime' => $now->format('d/m/Y H:i:s'),
            'year' => $now->format('Y'),
            'month' => $now->format('m'),
            'day' => $now->format('d'),
            'timezone' => $timezone,
            'timestamp' => $now->timestamp,
            'timezone_name' => $now->format('T'),
            'utc_offset' => $now->format('P')
        ];
    }

    /**
     * Genera la estructura de carpetas para reportes
     */
    public static function generateReportPath(string $basePath = null): string
    {
        $dateInfo = self::getCurrentDateTime();
        $basePath = $basePath ?? base_path('REPORTES');
        
        $path = $basePath . '/' . $dateInfo['year'] . '/' . $dateInfo['month'] . '/' . $dateInfo['day'];
        
        // Crear la estructura de carpetas si no existe
        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }
        
        return $path;
    }

    /**
     * Genera un nombre de archivo para reporte con fecha y hora
     */
    public static function generateReportFilename(string $reportName): string
    {
        $dateInfo = self::getCurrentDateTime();
        $sanitizedName = strtoupper(preg_replace('/[^a-zA-Z0-9_]/', '_', $reportName));
        
        return $dateInfo['year'] . '-' . $dateInfo['month'] . '-' . $dateInfo['day'] . '_' . 
               str_replace(':', '-', $dateInfo['time']) . '_' . $sanitizedName . '.md';
    }

    /**
     * Crea un reporte con el formato estándar
     */
    public static function createReport(string $reportName, string $content, array $metadata = []): string
    {
        $dateInfo = self::getCurrentDateTime();
        $path = self::generateReportPath();
        $filename = self::generateReportFilename($reportName);
        $filepath = $path . '/' . $filename;

        // Metadatos por defecto
        $defaultMetadata = [
            'type' => 'Reporte General',
            'status' => 'Completado',
            'duration' => 'N/A',
            'priority' => 'Normal'
        ];

        $metadata = array_merge($defaultMetadata, $metadata);

        // Crear el contenido del reporte
        $reportContent = "# 📋 REPORTE - " . strtoupper($reportName) . "\n";
        $reportContent .= "**Fecha:** " . $dateInfo['formatted_date'] . "\n";
        $reportContent .= "**Hora:** " . $dateInfo['time'] . "\n";
        $reportContent .= "**Tipo:** " . $metadata['type'] . "\n";
        $reportContent .= "**Estado:** " . $metadata['status'] . "\n";
        $reportContent .= "**Zona Horaria:** " . $dateInfo['timezone'] . "\n\n";
        
        $reportContent .= $content . "\n\n";
        
        $reportContent .= "---\n\n";
        $reportContent .= "**Estado:** " . $metadata['status'] . "\n";
        $reportContent .= "**Tiempo de resolución:** " . $metadata['duration'] . "\n";
        $reportContent .= "**Prioridad:** " . $metadata['priority'] . "\n";
        $reportContent .= "**Timestamp:** " . $dateInfo['timestamp'] . "\n";

        // Escribir el archivo
        File::put($filepath, $reportContent);

        return $filepath;
    }

    /**
     * Actualiza el índice del día
     */
    public static function updateDayIndex(string $reportName, array $metadata = []): void
    {
        $dateInfo = self::getCurrentDateTime();
        $path = self::generateReportPath();
        $indexPath = $path . '/INDEX.md';

        $defaultMetadata = [
            'type' => 'Reporte General',
            'status' => 'Completado',
            'duration' => 'N/A'
        ];

        $metadata = array_merge($defaultMetadata, $metadata);

        // Si el índice no existe, crearlo
        if (!File::exists($indexPath)) {
            $indexContent = self::generateDayIndexHeader($dateInfo);
            File::put($indexPath, $indexContent);
        }

        // Leer el índice actual
        $indexContent = File::get($indexPath);

        // Agregar el nuevo reporte
        $newEntry = "\n### 🕐 **" . $dateInfo['time'] . "** - [" . strtoupper($reportName) . ".md](./" . self::generateReportFilename($reportName) . ")\n";
        $newEntry .= "- **Tipo:** " . $metadata['type'] . "\n";
        $newEntry .= "- **Estado:** " . $metadata['status'] . "\n";
        $newEntry .= "- **Descripción:** " . ($metadata['description'] ?? 'Reporte del sistema') . "\n";
        $newEntry .= "- **Tiempo de resolución:** " . $metadata['duration'] . "\n\n";

        // Insertar antes del cierre del archivo
        $indexContent = str_replace('---', $newEntry . '---', $indexContent);

        File::put($indexPath, $indexContent);
    }

    /**
     * Genera el encabezado del índice del día
     */
    private static function generateDayIndexHeader(array $dateInfo): string
    {
        $content = "# 📋 ÍNDICE DE REPORTES - " . $dateInfo['day'] . " de " . self::getMonthName($dateInfo['month']) . ", " . $dateInfo['year'] . "\n\n";
        $content .= "## 📅 **Fecha:** " . $dateInfo['formatted_date'] . "\n";
        $content .= "**Total de Reportes:** 0\n\n";
        $content .= "---\n\n";
        $content .= "## 📊 **LISTA DE REPORTES DEL DÍA**\n\n";
        $content .= "---\n\n";
        $content .= "## 📈 **RESUMEN DEL DÍA**\n\n";
        $content .= "### ✅ **Problemas Resueltos**\n";
        $content .= "- [ ] Sin problemas reportados\n\n";
        $content .= "### 🎯 **Mejoras Implementadas**\n";
        $content .= "- [ ] Sin mejoras reportadas\n\n";
        $content .= "### 📊 **Estadísticas**\n";
        $content .= "- **Errores críticos resueltos:** 0\n";
        $content .= "- **Warnings restantes:** N/A\n";
        $content .= "- **Archivos reorganizados:** 0\n";
        $content .= "- **Tiempo total de trabajo:** N/A\n\n";
        $content .= "---\n\n";
        $content .= "## 🔄 **PRÓXIMOS PASOS**\n";
        $content .= "- Continuar desarrollo del sistema\n\n";
        $content .= "---\n\n";
        $content .= "**Última actualización:** " . $dateInfo['formatted_date'] . " - " . $dateInfo['time'] . "\n";
        $content .= "**Responsable:** Sistema de Reportes Automatizado\n";

        return $content;
    }

    /**
     * Obtiene el nombre del mes en español
     */
    private static function getMonthName(string $month): string
    {
        $months = [
            '01' => 'Enero', '02' => 'Febrero', '03' => 'Marzo',
            '04' => 'Abril', '05' => 'Mayo', '06' => 'Junio',
            '07' => 'Julio', '08' => 'Agosto', '09' => 'Septiembre',
            '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre'
        ];

        return $months[$month] ?? 'Mes';
    }

    /**
     * Obtiene información de fecha para el frontend
     */
    public static function getDateInfoForFrontend(): array
    {
        return self::getCurrentDateTime();
    }
}
