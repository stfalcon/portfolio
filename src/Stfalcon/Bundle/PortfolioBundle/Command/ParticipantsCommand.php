<?php

namespace Stfalcon\Bundle\PortfolioBundle\Command;

use Application\Bundle\UserBundle\Entity\User;
use Application\Bundle\UserBundle\Entity\UserTranslation;
use Doctrine\ORM\PersistentCollection;
use Stfalcon\Bundle\PortfolioBundle\Entity\Project;
use Stfalcon\Bundle\PortfolioBundle\Entity\UserWithPosition;
use Stfalcon\Bundle\PortfolioBundle\Entity\UserWithPositionTranslation;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * ParticipantsCommand class
 *
 * @author Oleg Kachinsky <logansoleg@gmail.com>
 */
class ParticipantsCommand extends ContainerAwareCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('portfolio:project:participants')
            ->setDescription('Move project participants to another table')
            ->setHelp(<<<'HELP'
The <info>%command.name%</info> move project participants to table where users have custom positions.
HELP
            );
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $projects = $em->getRepository('StfalconPortfolioBundle:Project')->findAll();

        foreach ($projects as $project) {
            $participants = $project->getUsersWithPositions();

            if (!$participants->isEmpty()) {
                /** @var UserWithPosition $participant */
                foreach ($participants as $participant) {
                    if ($this->hasUserWithPositionTranslations($participant)) {
                        continue;
                    }

                    $translation = (new UserWithPositionTranslation())
                        ->setField('position')
                        ->setLocale('en')
                        ->setContent($this->getTranslatedUserPosition($participant->getUser()));

                    $participant->addTranslation($translation);

                    $output->writeln(sprintf(
                        '<comment>User with position for Proejct#<info>%s</info> <info>successfully</info> created</comment>',
                        $project->getId()
                    ));
                }

                $em->flush();
            } else {
                $output->writeln(sprintf(
                    '<comment>Project #<info>%s</info> without participants</comment>',
                    $project->getId()
                ));
            }
        }
    }

    /**
     * @param User $user
     *
     * @return string
     */
    private function getTranslatedUserPosition(User $user)
    {
        $position = $user->getPosition();

        /** @var UserTranslation $translation */
        foreach ($user->getTranslations()->getValues() as $translation) {
            if ($translation->getField() == 'position' && $translation->getLocale() == 'en') {
                $position = $translation->getContent();
            }
        }

        return $position;
    }

    /**
     * @param UserWithPosition $userWithPosition
     *
     * @return bool
     */
    private function hasUserWithPositionTranslations($userWithPosition)
    {
        /** @var UserWithPositionTranslation $translation */
        foreach ($userWithPosition->getTranslations() as $translation) {
            if ($translation->getField() == 'position' && $translation->getLocale() == 'en') {
                return true;
            }
        }
        return false;
    }
}
