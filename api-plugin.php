<?php

namespace NamePlugin;

class NameApi {
    public $api_url;

    public function getVacancies($post, $vacancyId = 0)
    {
        global $wpdb;

        if (!is_object($post))
        {
            return false;
        }

        $page = 0;
        $allVacancies = [];
        $foundVacancy = null;

        do {
            $params =
                [
                'status' => 'all',
                'id_user' => $this->getOption('superjob_user_id'),
                'with_new_response' => 0,
                'order_field' => 'date',
                'order_direction' => 'desc',
                'page' => $page,
                'count' => 100
            ];
            $queryString = http_build_query($params);
            $response = $this->apiRequest($this->api_url . '/hr/vacancies/?' . $queryString);
            $responseObject = json_decode($response);

            if ($response !== false && is_object($responseObject) && isset($responseObject->objects))
            {
                $allVacancies = array_merge($allVacancies, $responseObject->objects);
                if ($vacancyId > 0)
                {
                    foreach ($responseObject->objects as $vacancy)
                    {
                        if ($vacancy->id == $vacancyId)
                        {
                            $foundVacancy = $vacancy;
                            break 2;
                        }
                    }
                }
                $page++;
            } else {
                break;
            }

        } while ($responseObject->more);

        return $vacancyId > 0 ? $foundVacancy : $allVacancies;
    }

    protected function apiRequest($url)
    {
        return '';
    }

    protected function getOption($optionName)
    {
        return '';
    }
}
