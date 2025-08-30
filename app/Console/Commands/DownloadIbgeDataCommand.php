<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class DownloadIbgeDataCommand extends Command
{
    protected $signature = 'ibge:cache';

    protected $description = 'Baixa os dados de Estados e Municípios do IBGE e salva em storage/app/private/ibge/';

    public function handle(): int
    {
        $this->info('Baixando estados do IBGE...');
        $statesRes = Http::get('https://servicodados.ibge.gov.br/api/v1/localidades/estados?orderBy=nome');

        if ($statesRes->failed()) {
            $this->error('Falha ao baixar estados.');
            return 1;
        }

        $states = $statesRes->json();
        Storage::put('ibge/estados.json', json_encode($states, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        $this->info('Estados salvos em storage/app/private/ibge/estados.json');

        $this->info('Baixando municípios do IBGE...');
        $citiesRes = Http::get('https://servicodados.ibge.gov.br/api/v1/localidades/municipios');

        if ($citiesRes->failed()) {
            $this->error('Falha ao baixar municípios.');
            return 1;
        }

        $cities = $citiesRes->json();
        Storage::put('ibge/municipios.json', json_encode($cities, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        $this->info('Municípios salvos em storage/app/private/ibge/municipios.json');

        $this->info('Gerando arquivos individuais por estado...');
        foreach ($states as $state) {
            $id = $state['id'];
            $filtered = collect($cities)->filter(fn($c) => ($c['microrregiao']['mesorregiao']['UF']['id'] ?? null) === $id
            )->values()->all();

            Storage::put("ibge/municipios_{$id}.json", json_encode($filtered, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            $this->line("Estado {$state['sigla']} ({$id}) salvo");
        }

        $this->info('Cache IBGE atualizado com sucesso!');
        return 0;
    }
}
