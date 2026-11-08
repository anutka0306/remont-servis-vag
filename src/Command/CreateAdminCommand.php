<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-admin',
    description: 'Create an administrator account',
)]
class CreateAdminCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $passwordHasher,
    ) {
        parent::__construct();
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {
        $io = new SymfonyStyle($input, $output);

        $email = strtolower(trim($io->ask('Email администратора')));

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $io->error('Укажите корректный email.');
            return Command::FAILURE;
        }

        if ($this->userRepository->findOneBy(['email' => $email])) {
            $io->error('Пользователь с таким email уже существует.');
            return Command::FAILURE;
        }

        $password = $io->askHidden('Введите пароль администратора');

        if (!is_string($password) || strlen($password) < 12) {
            $io->error('Пароль должен содержать минимум 12 символов.');
            return Command::FAILURE;
        }

        $user = new User();
        $user->setEmail($email);
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword(
            $this->passwordHasher->hashPassword($user, $password)
        );

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $io->success('Администратор успешно создан.');

        return Command::SUCCESS;
    }
}
