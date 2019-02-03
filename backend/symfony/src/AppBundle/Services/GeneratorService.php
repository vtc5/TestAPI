<?php
/**
 * Created by PhpStorm.
 * User: vtc
 * Date: 29.12.2018
 * Time: 8:51
 */

namespace AppBundle\Services;


use AppBundle\Entity\Admin;
use AppBundle\Entity\Company;
use AppBundle\Entity\TransferLog;
use AppBundle\Entity\User;

class GeneratorService extends BaseService
{

  /** @var \Faker\Factory */
  protected $fakeGenerator;

  public function __construct($em, $adminService, $fakeGenerator)
  {
    parent::__construct($em, $adminService);
    $this->fakeGenerator = $fakeGenerator;
  }


  public function generate($key) {
    $admin = $this->adminService->authenticateBySession($key);
    if ($admin instanceof Admin) {
      $connection = $this->em->getConnection();
      $connection->executeQuery('DELETE FROM transfer_log;');
      $connection->executeQuery('DELETE FROM users;');
      $connection->executeQuery('DELETE FROM companies;');
      $faker = \Faker\Factory::create();
      for ($iCompany=0; $iCompany<25; $iCompany++) {
        $company = new Company();
        $company->setName($faker->company);
        $quota = $faker->numberBetween(5, 15);
        $company->setQuota($quota*1024*1024*1024);
        $this->em->persist($company);
        $numberOfusers = $faker->numberBetween(5,15);
        for ($iUser=0; $iUser<$numberOfusers; $iUser++) {
          $user = new User();
          $user->setName($faker->name());
          $user->setEmail($faker->email);
          $user->setCompany($company);
          $this->em->persist($user);
          for ($iMonth=0; $iMonth<12; $iMonth++) {
            $transfersInMonth =$faker->numberBetween(20,150);
            for ($iTransfer=0; $iTransfer<$transfersInMonth; $iTransfer++) {
              $date = $faker->dateTimeBetween('-'.($iMonth+1).' month','-'.$iMonth.' month');
              $transfer = new TransferLog();
              $transfer->setDateTime($date);
              $transfer->setUser($user);
              $transfer->setResourse($faker->url);
              $transfer->setTransfered($faker->numberBetween(100, 10*1024*1024*1024));
              $this->em->persist($transfer);
            }
          }
        }
        $this->em->flush();
        $this->em->clear();
        gc_collect_cycles();
      }
    }
    return null;
  }
}