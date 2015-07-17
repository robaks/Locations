<?php

namespace RoLocations\Controller\User;

use T4webActionInjections\Mvc\Controller\AbstractActionController;
use Zend\Mvc\Controller\Plugin\Params;
use T4webBase\Domain\Collection;
use Zend\View\Model\JsonModel;
use T4webBase\Domain\Service\BaseFinder;

class IndexAjaxController extends AbstractActionController {

    /**
     * @var JsonModel
     */
    protected $view;

    public function __construct() {
        $this->view = new JsonModel();
    }

    public function getLocationCountryAction(BaseFinder $countryFinderService) {
        $countries = $countryFinderService->findMany(['RoLocations' => ['Countries' => []]]);

        return $this->resultLocation($countries);
    }

    public function getLocationRegionAction(Params $params, BaseFinder $regionFinderService) {
        $dataLocation = $params->fromPost();
        $regions = $regionFinderService->findMany(['RoLocations' => ['Regions' => ['countryId' => $dataLocation['id']]]]);

        return $this->resultLocation($regions);
    }

    public function getLocationCityAction(Params $params, BaseFinder $cityFinderService) {
        $dataLocation = $params->fromPost();
        $cities = $cityFinderService->findMany(['RoLocations' => ['Cities' => ['regionId' => $dataLocation['id']]]]);

        return $this->resultLocation($cities);
    }

    private function resultLocation(Collection $location) {
        if(!$location->count()) {
            $this->setError('error');
            return $this->view;
        }

        $this->view->locations = $location;

        return $this->view;
    }

    public function getLocationsByCityAction(Params $params, BaseFinder $cityFinderService, BaseFinder $regionFinderService, BaseFinder $countryFinderService) {
        $dataLocation = $params->fromPost();

        $city = $cityFinderService->find(['RoLocations' => ['Cities' => ['Id' => $dataLocation['id']]]]);
        if(!$city) {
            $this->setError('error');
            return $this->view;
        }

        $this->view->countries = $countryFinderService->findMany(['RoLocations' => ['Countries' => []]]);
        $this->view->regions = $regionFinderService->findMany(['RoLocations' => ['Regions' => ['countryId' => $city->getCountryId()]]]);
        $this->view->cities = $cityFinderService->findMany(['RoLocations' => ['Cities' => ['regionId' => $city->getRegionId()]]]);
        $this->view->locations = $city->extract();

        return $this->view;
    }

    protected function setError($messages, $code = 500) {
        $this->response->setStatusCode($code);
        $this->view->messages = $messages;
    }

}
