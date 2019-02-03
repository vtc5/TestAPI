<?php
/**
 * Created by PhpStorm.
 * User: vtc
 * Date: 29.12.2018
 * Time: 8:51
 */

namespace AppBundle\Services;

use AppBundle\Entity\Admin;

class ReportService extends BaseService
{

  public function createReport($key, $month) {
    $admin = $this->adminService->authenticateBySession($key);
    if ($admin instanceof Admin) {
      $month = strtolower($month);
      $this->checkRequest($month);
      $report = array();
      if (empty($this->errors)) {
        $startDate = new \DateTime('first day of '.$month);
        $endDate = new \DateTime('last day of '.$month);
        $endDate->setTime(23,59,59);
        $today = new \DateTime('today');
        if ($startDate>$today) {
          $startDate->modify('-1 year');
          $endDate->modify('-1 year');
        }
        $connection = $this->em->getConnection();
        $query = $connection->prepare("
          SELECT * FROM (
          SELECT 
            c.id, 
            c.name, 
            c.quota AS quota, 
            SUM(t.transfered) AS transfered
          FROM transfer_log t
          LEFT JOIN users u ON u.id=t.user_id
          LEFT JOIN companies c ON c.id=u.company_id
          WHERE t.date_time>=:start and t.date_time<=:end 
          GROUP BY c.id
          ORDER BY c.name) r WHERE r.quota<r.transfered
      ");
        $query->bindValue('start', $startDate->format('Y-m-d H:i:s'));
        $query->bindValue('end', $endDate->format('Y-m-d H:i:s'));
        $query->execute();
        $reportRecords = $query->fetchAll();
        foreach ($reportRecords as $reportRecord) {
          $record = new \stdClass();
          $record->name = $reportRecord['name'];
          $record->quota = $reportRecord['quota']/(1024*1024*1024);
          $record->transfered = round($reportRecord['transfered']/(1024*1024*1024), 2);
          $report[] = $record;
        }
      }
      return array(
        'report' => $report,
        'errors' => $this->errors
      );
    }
    return null;
  }

  protected function checkRequest($month) {
    $monthes = array(
      'january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december'
    );
    if (!in_array($month, $monthes)) {
      $this->createError(16,"Unknown month'".$month."'");
    }
  }
}