<?php
/**
 * Created by PhpStorm.
 * User: vtc
 * Date: 03.02.2019
 * Time: 17:05
 */

namespace AppBundle\Command;


use AppBundle\Entity\Admin;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class addUserCommand extends ContainerAwareCommand
{
  protected function configure()
  {
    parent::configure();

    $this->setName('addUser')
      ->setDescription('Add/Update user')
      ->addArgument('username', InputArgument::REQUIRED, 'Username')
      ->addArgument('email', InputArgument::REQUIRED, 'E-Mail')
      ->addArgument('password', InputArgument::REQUIRED, 'Password')
      ;
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $username = $input->getArgument('username');
    $email = $input->getArgument('email');
    $password = $input->getArgument('password');
    $doctrine = $this->getContainer()->get('doctrine')->getManager();
    $admin = $doctrine->getRepository('AppBundle:Admin')
      ->findOneBy(array(
          'username'=>$username
        ));
    if (empty($admin)) {
      $admin = new Admin();
      $admin->setUsername($username);
      $output->write('New user: '.$username."\n");
    }
    $admin->setEmail($email);
    $admin->setPassword($password);
    $doctrine->persist($admin);
    $doctrine->flush();
    $output->write('User: '.$username." was saved!\n");
  }


}