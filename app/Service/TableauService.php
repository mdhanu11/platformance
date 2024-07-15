<?php

namespace App\Service;

use App\Exceptions\CustomException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TableauService
{
    private $baseUrl = '';
    private $patSecret = '';
    private $patName = '';

    private $contentUrl = '';

    private $siteUrl = '';

    public function __construct()
    {
        $this->baseUrl = config('services.tableau.site_url').'/api/'.config('services.tableau.api_version');
        $this->patSecret = config('services.tableau.superadmin_pat_secret');
        $this->patName = config('services.tableau.pat_name');
        $this->contentUrl = config('services.tableau.content_url');
        $this->siteUrl = config('services.tableau.site_url');
    }

    public function loginToTableau(){

        $url = "$this->baseUrl/auth/signin";
        $payload = [
            'credentials' => [
                'personalAccessTokenName' => $this->patName,
                'personalAccessTokenSecret' => $this->patSecret,
                'site' => [
                    'contentUrl' => $this->contentUrl
                ]
            ]
        ];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post($url, $payload);

        if ($response->successful()) {
            $token = $response['credentials']['token'];
            $siteId = $response['credentials']['site']['id'];
            $tableauUserId = $response['credentials']['user']['id'];

            return [
                'token' => $token,
                'site_id' => $siteId,
                'tableau_user_id' => $tableauUserId
            ];
        } else {
            Log::error('Tableau sign-in failed', [
                'response' => $response->body()
            ]);
            throw new CustomException('Tableau sign-in failed',400);
        }
    }

    public function getProjects($token, $siteId)
    {
        $url = "$this->baseUrl/sites/{$siteId}/projects";

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'X-Tableau-Auth' => $token,
        ])->get($url);

        if ($response->successful()) {
            return $response->json()['projects']['project'];
        } else {
            Log::error('Tableau fetch projects failed', [
                'response' => $response->body()
            ]);
            throw new CustomException('Tableau fetch projects failed',400);
        }
    }

    public function getWorkbooksByProject($token, $siteId, $projectName)
    {
        $url = "$this->baseUrl/sites/{$siteId}/workbooks?filter=projectName:eq:".$projectName;

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'X-Tableau-Auth' => $token,
        ])->get($url);

        if ($response->successful()) {
            return $response->json()['workbooks']['workbook'];
        } else {
            Log::error('Tableau fetch workbooks by project failed', [
                'response' => $response->body()
            ]);
            throw new CustomException('Tableau fetch workbooks by project failed',400);
        }
    }
    public function getViewsByProject($token, $siteId, $projectName)
    {
        $url = "$this->baseUrl/sites/{$siteId}/views?filter=projectName:eq:".$projectName;

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'X-Tableau-Auth' => $token,
        ])->get($url);

        if ($response->successful()) {
            return $response->json()['views']['view'];
        } else {
            Log::error('Tableau fetch workbooks by project failed', [
                'response' => $response->body()
            ]);
            throw new CustomException('Tableau fetch workbooks by project failed',400);
        }
    }
    public function getViewUrl($viewContentUrl)
    {

        $response = Http::asForm()->post("$this->siteUrl/trusted", [
            'username' => "mdhanu11@gmail.com",
        ]);
        print_r($response->body());
        if ($response->failed()) {
            return response()->json(['error' => 'Failed to get Tableau token'], 500);
        }

        $token = $response->body();

        $viewUrl = "{$this->siteUrl}/trusted/{$token}/t/{$this->contentUrl}/views/{$viewContentUrl}?:embed=yes";

//        $url = "$this->siteUrl/#/site/$this->contentUrl/views/$viewContentUrl";
//        $modifiedurl = str_replace('/sheets', '', $viewUrl);
        return response()->json(
            ['viewUrl' => $viewUrl]
        );
    }
    public function getViewsByViewId($token, $siteId, $viewId)
    {
        $url = "$this->baseUrl/sites/$siteId/views/$viewId/data";
//        print_r($url);

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'X-Tableau-Auth' => $token,
        ])->get($url);

//        print_r(json_encode($response->status()));
        if ($response->successful()) {
//            print_r($response->body());
            return response()->json(
                [
                    'data' => $response->body()
                ]
            );
        } else {
            Log::error('Tableau fetch view failed', [
                'response' => $response->body()
            ]);
            throw new CustomException('Tableau fetch view failed',400);
        }
    }
}
