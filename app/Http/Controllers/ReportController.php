<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Lead;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    /**
     * Exibe a página principal de relatórios
     */
    public function index()
    {
        return view('reports.index');
    }

    /**
     * Exporta todos os clientes do usuário em CSV
     */
    public function exportClients()
    {
        $fileName = 'clientes_' . date('Y-m-d_H-i') . '.csv';

        return new StreamedResponse(function () {
            $handle = fopen('php://output', 'w');
            
            // BOM para Excel reconhecer acentuação UTF-8
            fputs($handle, "\xEF\xBB\xBF");

            // Cabeçalho
            fputcsv($handle, ['ID', 'Nome', 'Empresa', 'Email', 'Telefone', 'Cidade/UF', 'Data Cadastro'], ';');

            // Busca clientes em chunks para não estourar memória
            Client::chunk(500, function ($clients) use ($handle) {
                foreach ($clients as $client) {
                    fputcsv($handle, [
                        $client->id,
                        $client->name,
                        $client->company_name ?? '-',
                        $client->email,
                        $client->phone ?? '-',
                        ($client->city ?? '-') . '/' . ($client->state ?? '-'),
                        $client->created_at->format('d/m/Y H:i'),
                    ], ';');
                }
            });

            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

    /**
     * Exporta os negócios (leads) com filtros opcionais
     */
    public function exportLeads(Request $request)
    {
        $fileName = 'vendas_' . date('Y-m-d_H-i') . '.csv';

        return new StreamedResponse(function () use ($request) {
            $handle = fopen('php://output', 'w');
            
            // BOM para Excel
            fputs($handle, "\xEF\xBB\xBF");

            // Cabeçalho
            fputcsv($handle, ['ID', 'Título', 'Cliente', 'Valor', 'Status', 'Data Criação', 'Última Atualização'], ';');

            // Query base (UserScope já aplica filtro por usuário)
            $query = Lead::with('client');

            // Filtro por Status
            if ($request->has('status') && $request->status != 'all') {
                $query->where('status', $request->status);
            }

            // Filtro por Data (Opcional)
            if ($request->date_start) {
                $query->whereDate('created_at', '>=', $request->date_start);
            }
            if ($request->date_end) {
                $query->whereDate('created_at', '<=', $request->date_end);
            }

            // Processamento em Chunks
            $query->chunk(500, function ($leads) use ($handle) {
                foreach ($leads as $lead) {
                    // Traduz status se for Enum, ou usa string direta
                    $statusLabel = $lead->status instanceof \App\LeadStatus 
                        ? $lead->status->label() 
                        : $lead->status;

                    fputcsv($handle, [
                        $lead->id,
                        $lead->title,
                        $lead->client->name ?? 'Cliente Removido',
                        number_format($lead->value, 2, ',', '.'),
                        $statusLabel,
                        $lead->created_at->format('d/m/Y'),
                        $lead->updated_at->format('d/m/Y H:i'),
                    ], ';');
                }
            });

            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }
}
