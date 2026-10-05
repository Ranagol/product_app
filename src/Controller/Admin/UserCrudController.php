<?php

namespace App\Controller\Admin;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

#[IsGranted('ROLE_ADMIN')]
class UserCrudController extends AbstractCrudController
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    /**
     * Here we set up which fields do we want to see in the admin panel for the User entity. We want
     * is, name, email, and is_verified fields. Additionally, we include a password field for
     * setting a plain password.
     */
    public function configureFields(string $pageName): iterable
    {
        $passwordField = $this->doAndorPasswordSettings($pageName);

        $rolesField = $this->doAndorRolesSettings($pageName);

        return [
            IdField::new('id'),
            TextField::new('name'),
            // TextEditorField::new('description'),
            EmailField::new('email'),
            BooleanField::new('is_verified'),
            $passwordField,
            $rolesField,
        ];
    }

    /**
     * Here we want to add a roles field in the user admin panel.
     */
    public function doAndorRolesSettings(): ChoiceField
    {
        $rolesField = ChoiceField::new('roles', 'Roles')
            ->onlyOnForms()
            ->setChoices([
                'User' => 'ROLE_USER',
                'Admin' => 'ROLE_ADMIN',
            ])
            ->renderExpanded() // Render the choices as checkboxes instead of a dropdown
            ->allowMultipleChoices();

        return $rolesField;
    }

    /**
     * Custom setup for the password field in the user admin panel.
     */
    public function doAndorPasswordSettings(string $pageName): TextField
    {
        // We want to add password field for the user to set a plain password
        $passwordField = TextField::new('plainpassword', 'password')

            //The field should be a password type input
            ->setFormType(PasswordType::class)
            ->onlyOnForms()

            /**
             * setRequired: the field is required only when creating a new user
             * $pageName = the current page type (passed as argument to this configureFields() method)
             * Crud::PAGE_NEW = EasyAdmin constant representing the "Create New Entity" page
             */
            ->setRequired($pageName === Crud::PAGE_NEW);

        // We only want to show this password field on the edit page
        if ($pageName === Crud::PAGE_EDIT) {

            // Add a help message for the password field on the edit page
            $passwordField->setHelp('Leave blank to keep the current password');
        }

        return $passwordField;
    }

    /**
     * When we create a new use in the admin panel, we want its password to be hashed, before we
     * save this new user to the database.
     */
    public function persistEntity(EntityManagerInterface $entityManager, object $entityInstance): void
    {
        $hashedPassword = $this->passwordHasher->hashPassword(
            $entityInstance,
            $entityInstance->getPlainPassword()
        );

        $entityInstance->setPassword($hashedPassword);

        parent::persistEntity($entityManager, $entityInstance);
    }

    /**
     * This is for updating the user in admin panel. Here too, we want the password to be hashed
     * before saving the user.
     */
    public function updateEntity(EntityManagerInterface $entityManager, object $entityInstance): void
    {
        if ($entityInstance->getPlainPassword() === null) {
            return;
        }

        $hashedPassword = $this->passwordHasher->hashPassword(
            $entityInstance,
            $entityInstance->getPlainPassword()
        );

        $entityInstance->setPassword($hashedPassword);

        parent::updateEntity($entityManager, $entityInstance);
    }
}
