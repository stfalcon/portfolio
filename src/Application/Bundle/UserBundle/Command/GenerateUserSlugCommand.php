<?php

namespace Application\Bundle\UserBundle\Command;

use Application\Bundle\UserBundle\Entity\User;
use Application\Bundle\UserBundle\Entity\UserTranslation;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * GenerateUserSlugCommand
 *
 * @author Oleg Kachinsky <logansoleg@gmail.com>
 */
class GenerateUserSlugCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('stfalcon:user:generate:slug')
            ->setDescription('Generate slug for all users')
            ->setHelp("The <info>%command.name%</info> command generate slug for all users.");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $userRepository = $em->getRepository('ApplicationUserBundle:User');
        $users          = $userRepository->findAll();

        /** @var User $user */
        foreach ($users as $user) {
            $isFirstnameExists = false;
            $isLastnameExists  = false;

            $slug = '';

            $translations = $user->getTranslations()->getValues();
            /** @var UserTranslation $translation */
            foreach ($translations as $translation) {
                if ($translation->getLocale() == 'en'
                    && in_array($translation->getField(), ['firstname', 'lastname'])) {
                    $tanslationContent = $translation->getContent();

                    if ($translation->getField() == 'firstname') {
                        $isFirstnameExists = !empty($tanslationContent) ? true : false;

                        $slug = $tanslationContent.'_'.$slug;
                    } else {
                        $isLastnameExists = !empty($tanslationContent) ? true : false;

                        $slug = $slug.$tanslationContent;
                    }
                }
            }

            if ($isFirstnameExists && $isLastnameExists) {
                $user->setSlug($slug);

                $this->showResultMessage($output, ($isFirstnameExists && $isLastnameExists), $user, $slug);
            } else {
                $this->showResultMessage($output, ($isFirstnameExists && $isLastnameExists), $user, $slug);
            }
        }

        $em->flush();
    }

    /**
     * Show result message
     *
     * @param OutputInterface $output Output
     * @param bool            $result Result
     * @param User            $user   User
     * @param string          $slug   Slug
     *
     * @throws \Exception
     */
    private function showResultMessage(OutputInterface $output, $result, User $user, $slug)
    {
        if (true === $result) {
            $output->writeln(sprintf(
                '<info>Success:</info> Slug successfully created! (User: <comment>"%s"</comment>, Slug: <comment>"%s"</comment>)',
                $user->getFirstname().' '.$user->getLastname(),
                $slug
            ));
        } elseif (false === $result) {
            $output->writeln(sprintf(
                '<error>Error:</error>   Slug is not created!       (User: <comment>"%s"</comment>, Slug: <comment>"%s"</comment>)',
                $user->getFirstname().' '.$user->getLastname(),
                $slug
            ));
        } else {
            throw new \Exception('Unexpected type of result');
        }
    }
}
