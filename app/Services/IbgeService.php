<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class IbgeService
{
    public function getStates(): array
    {
        return Cache::remember('ibge:states', now()->addDay(), function () {
            $res = Http::timeout(5)->get('https://servicodados.ibge.gov.br/api/v1/localidades/estados?orderBy=nome');

            if ($res->failed()) {
                return $this->fallbackStates();
            }

            return collect($res->json())->map(fn($s) => [
                'id' => $s['id'],
                'nome' => $s['nome'],
                'sigla' => $s['sigla']
            ])->toArray();
        });
    }

    public function getCitiesByState(int $stateId): array
    {
        return Cache::remember("ibge:cities:$stateId", now()->addDay(), function () use ($stateId) {
            $res = Http::timeout(5)->get("https://servicodados.ibge.gov.br/api/v1/localidades/estados/{$stateId}/municipios");

            if ($res->failed()) {
                return $this->fallbackCities($stateId);
            }

            return collect($res->json())->map(fn($c) => [
                'id' => $c['id'],
                'nome' => $c['nome']
            ])->toArray();
        });
    }

    private function fallbackStates(): array
    {
        $statesPath = storage_path('app/private/ibge/estados.json');

        if (is_file($statesPath)) {
            $data = json_decode(file_get_contents($statesPath), true, 512, JSON_THROW_ON_ERROR) ?: [];
            return collect($data)->map(fn($s) => [
                'id' => $s['id'],
                'nome' => $s['nome'],
                'sigla' => $s['sigla']
            ])->toArray();
        }

        return [
            ['id' => 12, 'nome' => 'Acre', 'sigla' => 'AC'],
            ['id' => 27, 'nome' => 'Alagoas', 'sigla' => 'AL'],
            ['id' => 16, 'nome' => 'Amapá', 'sigla' => 'AP'],
            ['id' => 13, 'nome' => 'Amazonas', 'sigla' => 'AM'],
            ['id' => 29, 'nome' => 'Bahia', 'sigla' => 'BA'],
            ['id' => 23, 'nome' => 'Ceará', 'sigla' => 'CE'],
            ['id' => 53, 'nome' => 'Distrito Federal', 'sigla' => 'DF'],
            ['id' => 32, 'nome' => 'Espírito Santo', 'sigla' => 'ES'],
            ['id' => 52, 'nome' => 'Goiás', 'sigla' => 'GO'],
            ['id' => 21, 'nome' => 'Maranhão', 'sigla' => 'MA'],
            ['id' => 51, 'nome' => 'Mato Grosso', 'sigla' => 'MT'],
            ['id' => 50, 'nome' => 'Mato Grosso do Sul', 'sigla' => 'MS'],
            ['id' => 31, 'nome' => 'Minas Gerais', 'sigla' => 'MG'],
            ['id' => 15, 'nome' => 'Pará', 'sigla' => 'PA'],
            ['id' => 25, 'nome' => 'Paraíba', 'sigla' => 'PB'],
            ['id' => 41, 'nome' => 'Paraná', 'sigla' => 'PR'],
            ['id' => 26, 'nome' => 'Pernambuco', 'sigla' => 'PE'],
            ['id' => 22, 'nome' => 'Piauí', 'sigla' => 'PI'],
            ['id' => 33, 'nome' => 'Rio de Janeiro', 'sigla' => 'RJ'],
            ['id' => 24, 'nome' => 'Rio Grande do Norte', 'sigla' => 'RN'],
            ['id' => 43, 'nome' => 'Rio Grande do Sul', 'sigla' => 'RS'],
            ['id' => 11, 'nome' => 'Rondônia', 'sigla' => 'RO'],
            ['id' => 14, 'nome' => 'Roraima', 'sigla' => 'RR'],
            ['id' => 42, 'nome' => 'Santa Catarina', 'sigla' => 'SC'],
            ['id' => 35, 'nome' => 'São Paulo', 'sigla' => 'SP'],
            ['id' => 28, 'nome' => 'Sergipe', 'sigla' => 'SE'],
            ['id' => 17, 'nome' => 'Tocantins', 'sigla' => 'TO'],
        ];
    }

    private function fallbackCities(int $statedId): array
    {
        $byUfPath = storage_path("app/private/ibge/municipios_{$statedId}.json");

        if (is_file($byUfPath)) {
            $data = json_decode(file_get_contents($byUfPath), true, 512, JSON_THROW_ON_ERROR) ?: [];
            return collect($data)->map(fn($c) => [
                'id' => $c['id'] ?? null,
                'nome' => $c['nome'] ?? '',
            ])->toArray();
        }

        return [];
    }
}
