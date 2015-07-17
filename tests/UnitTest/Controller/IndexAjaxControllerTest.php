<?php
namespace RoLocations\UnitTest\Controller\User;

use RoLocations\Cities\Cities;
use RoLocations\Countries\Countries;
use RoLocations\Regions\Regions;
use T4webBase\Domain\Collection;

class IndexAjaxControllerTest extends \PHPUnit_Framework_TestCase {
    
    private $controller;
    private $paramsMock;
    private $countryFinderServiceMock;
    private $regionFinderServiceMock;
    private $cityFinderServiceMock;

    public function setUp(){
        $this->paramsMock = $this->getMock('Zend\Mvc\Controller\Plugin\Params');

        $this->countryFinderServiceMock = $this->getMockBuilder('T4webBase\Domain\Service\BaseFinder')
                ->disableOriginalConstructor()
                ->getMock();
        
        $this->regionFinderServiceMock = $this->getMockBuilder('T4webBase\Domain\Service\BaseFinder')
                ->disableOriginalConstructor()
                ->getMock();
        
        $this->cityFinderServiceMock = $this->getMockBuilder('T4webBase\Domain\Service\BaseFinder')
                ->disableOriginalConstructor()
                ->getMock();

        $this->controller = $this->getMock('\RoLocations\Controller\User\IndexAjaxController', ['setError'], []);
    }

    public function testGetLocationsByCityWithEmptyCollection() {
        $dataLocation = array('id' => 5);

        $this->paramsMock->expects($this->once())
            ->method('fromPost')
            ->willReturn($dataLocation);

        $this->cityFinderServiceMock->expects($this->once())
            ->method('find')
            ->with($this->equalTo(array('RoLocations' => array('Cities' => array('Id' => $dataLocation['id'])))))
            ->willReturn(null);

        $this->controller->expects($this->once())
            ->method('setError')
            ->with($this->equalTo('error'));

        $return = $this->controller->getLocationsByCityAction($this->paramsMock, $this->cityFinderServiceMock, $this->regionFinderServiceMock, $this->countryFinderServiceMock);

        $this->assertInstanceOf('Zend\View\Model\JsonModel', $return);
    }

    public function testGetLocationsByCity() {
        $dataLocation = array('id' => 5);
        $city = new Cities(array('id' => 5, 'regionId' => 9, 'countryId' => 7));
        $region = new Regions(array('id' => 9));
        $country = new Countries(array('id' => 7));

        $collectionCityByRegion = new Collection();
        $collectionRegion = new Collection();
        $collectionCountry = new Collection();

        $collectionRegion->append($region);
        $collectionCountry->append($country);

        $this->paramsMock->expects($this->once())
            ->method('fromPost')
            ->will($this->returnValue($dataLocation));

        $this->cityFinderServiceMock->expects($this->once())
            ->method('find')
            ->with($this->equalTo(array('RoLocations' => array('Cities' => array('Id' => $dataLocation['id'])))))
            ->will($this->returnValue($city));

        $this->countryFinderServiceMock->expects($this->once())
            ->method('findMany')
            ->with($this->equalTo(array('RoLocations' => array('Countries' => array()))))
            ->will($this->returnValue($collectionCountry));

        $this->regionFinderServiceMock->expects($this->once())
            ->method('findMany')
            ->with($this->equalTo(array('RoLocations' => array('Regions' => array('countryId' => $city->getCountryId())))))
            ->will($this->returnValue($collectionRegion));

        $this->cityFinderServiceMock->expects($this->once())
            ->method('findMany')
            ->with($this->equalTo(array('RoLocations' => array('Cities' => array('regionId' => $city->getRegionId())))))
            ->will($this->returnValue($collectionCityByRegion));

        $resultView = $this->controller->getLocationsByCityAction($this->paramsMock, $this->cityFinderServiceMock, $this->regionFinderServiceMock, $this->countryFinderServiceMock);

        $this->assertSame($collectionCountry, $resultView->countries);
        $this->assertSame($collectionCityByRegion, $resultView->cities);
        $this->assertSame($collectionRegion, $resultView->regions);
        $this->assertSame($city->extract(), $resultView->locations);
    }

    public function testGetLocationCountryAction() {
        $country = new Countries(array('id' => 7));
        $collectionCountry = new Collection();
        $collectionCountry->append($country);

        $this->countryFinderServiceMock->expects($this->once())
            ->method('findMany')
            ->with($this->equalTo(array('RoLocations' => array('Countries' => array()))))
            ->will($this->returnValue($collectionCountry));

        $resultView = $this->controller->getLocationCountryAction($this->countryFinderServiceMock);

        $this->assertSame($collectionCountry, $resultView->locations);
    }

    public function testGetLocationCountryActionWithoutCollection() {
        $collectionCountry = new Collection();

        $this->countryFinderServiceMock->expects($this->once())
            ->method('findMany')
            ->with($this->equalTo(array('RoLocations' => array('Countries' => array()))))
            ->will($this->returnValue($collectionCountry));

        $this->controller->expects($this->once())
            ->method('setError')
            ->with($this->equalTo('error'));

        $this->controller->getLocationCountryAction($this->countryFinderServiceMock);

    }

    public function testGetLocationRegionAction() {
        $region = new Regions(array('id' => 7));
        $collectionRegion = new Collection();
        $collectionRegion->append($region);
        $dataLocation = array('id' => 5);

        $this->paramsMock->expects($this->once())
            ->method('fromPost')
            ->will($this->returnValue($dataLocation));

        $this->regionFinderServiceMock->expects($this->once())
            ->method('findMany')
            ->with($this->equalTo(array('RoLocations' => array('Regions' => array('countryId' => $dataLocation['id'])))))
            ->will($this->returnValue($collectionRegion));

        $resultView = $this->controller->getLocationRegionAction($this->paramsMock, $this->regionFinderServiceMock);

        $this->assertSame($collectionRegion, $resultView->locations);
    }

    public function testGetLocationCityAction() {
        $city = new Cities(array('id' => 7));
        $collectionCity = new Collection();
        $collectionCity->append($city);
        $dataLocation = array('id' => 5);

        $this->paramsMock->expects($this->once())
            ->method('fromPost')
            ->will($this->returnValue($dataLocation));

        $this->cityFinderServiceMock->expects($this->once())
            ->method('findMany')
            ->with($this->equalTo(array('RoLocations' => array('Cities' => array('regionId' => $dataLocation['id'])))))
            ->will($this->returnValue($collectionCity));

        $resultView = $this->controller->getLocationCityAction($this->paramsMock, $this->cityFinderServiceMock);

        $this->assertSame($collectionCity, $resultView->locations);
    }

}
