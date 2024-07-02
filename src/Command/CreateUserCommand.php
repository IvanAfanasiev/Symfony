<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-user',
    description: 'Creates a new user.',
    hidden: false,
    aliases: ['app:add-user']
)]
class CreateUserCommand extends Command
{
    private bool $requirePassword;

    public function __construct(
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $passwordHasher
    ) {
        parent::__construct();
    }

    protected function configure(): void{
        $this->setDescription('Creates a new user.')
        ->setHelp('This command allows you to create a user');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->writeln([
            'User Creator',
            '-=-=-=-=-=-=-',
            '',
        ]);

        while(true) {
            $email = $io->ask('Provide email for new user');
            
            $existingUser = $this->userRepository->findOneBy(array('email' => $email));
            if ($existingUser === null) {
                break;
            }
            $io->writeln('<error>User already exists!</error>');

        }

        $password = $io->ask('Password:');

        $user = new User();
        $user->setEmail($email);

        $hashedPassword = $this->passwordHasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);

        $this->userRepository->create($user);
        $io->writeln('<info>User created!</info>');
        $io->success('User ' . $email . ' has been successfully created! Password to log in: ' . $password);
        
        return Command::SUCCESS;
    }
}