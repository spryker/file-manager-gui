<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\FileManagerGui\Communication\Form;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\FileDirectoryLocalizedAttributesTransfer;
use Generated\Shared\Transfer\FileDirectoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\FileManagerGui\Communication\Form\FileDirectoryForm;
use Spryker\Zed\FileManagerGui\Communication\Form\FileDirectoryLocalizedAttributesForm;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Forms;
use Symfony\Component\Validator\Validation;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group FileManagerGui
 * @group Communication
 * @group Form
 * @group FileDirectoryFormTest
 * Add your own group annotations below this line
 */
class FileDirectoryFormTest extends Unit
{
    /**
     * @uses \Spryker\Zed\FileManagerGui\Communication\Form\FileDirectoryForm::FIELD_NAME_MAX_LENGTH
     * @uses \Spryker\Zed\FileManagerGui\Communication\Form\FileDirectoryLocalizedAttributesForm::FIELD_TITLE_MAX_LENGTH
     */
    protected const int FIELD_MAX_LENGTH = 255;

    protected const string LOCALE_NAME_ENGLISH = 'en_US';

    protected const string LOCALE_NAME_GERMAN = 'de_DE';

    protected FormFactoryInterface $formFactory;

    protected function _before(): void
    {
        $this->formFactory = Forms::createFormFactoryBuilder()
            ->addExtension(new ValidatorExtension(Validation::createValidator()))
            ->getFormFactory();
    }

    public function testGivenANameAndTitlesOfMaximumLengthWhenTheFileDirectoryFormIsSubmittedThenTheFormIsValid(): void
    {
        // Arrange
        $fileDirectoryForm = $this->createFileDirectoryForm();
        $valueOfMaximumLength = str_repeat('a', static::FIELD_MAX_LENGTH);

        // Act
        $fileDirectoryForm->submit($this->createSubmittedData($valueOfMaximumLength, $valueOfMaximumLength));

        // Assert
        $this->assertTrue($fileDirectoryForm->isValid());
    }

    public function testGivenABlankNameAndBlankTitlesWhenTheFileDirectoryFormIsSubmittedThenTheNameAndEveryTitleAreRejected(): void
    {
        // Arrange
        $fileDirectoryForm = $this->createFileDirectoryForm();

        // Act
        $fileDirectoryForm->submit($this->createSubmittedData('', ''));

        // Assert
        $this->assertFalse($fileDirectoryForm->isValid());
        $this->assertNameAndEveryTitleHaveErrors($fileDirectoryForm);
    }

    public function testGivenANameAndTitlesOverTheMaximumLengthWhenTheFileDirectoryFormIsSubmittedThenTheNameAndEveryTitleAreRejected(): void
    {
        // Arrange
        $fileDirectoryForm = $this->createFileDirectoryForm();
        $valueOverMaximumLength = str_repeat('a', static::FIELD_MAX_LENGTH + 1);

        // Act
        $fileDirectoryForm->submit($this->createSubmittedData($valueOverMaximumLength, $valueOverMaximumLength));

        // Assert
        $this->assertFalse($fileDirectoryForm->isValid());
        $this->assertNameAndEveryTitleHaveErrors($fileDirectoryForm);
    }

    protected function createFileDirectoryForm(): FormInterface
    {
        $fileDirectoryTransfer = new FileDirectoryTransfer();

        foreach ($this->getLocaleNames() as $localeName) {
            $fileDirectoryTransfer->addFileDirectoryLocalizedAttribute(
                (new FileDirectoryLocalizedAttributesTransfer())->setLocale((new LocaleTransfer())->setLocaleName($localeName)),
            );
        }

        return $this->formFactory->create(FileDirectoryForm::class, $fileDirectoryTransfer, [
            FileDirectoryForm::OPTION_AVAILABLE_LOCALES => [],
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    protected function createSubmittedData(string $name, string $title): array
    {
        $localizedAttributes = [];

        foreach ($this->getLocaleNames() as $localeName) {
            $localizedAttributes[] = [
                FileDirectoryLocalizedAttributesForm::FIELD_TITLE => $title,
                FileDirectoryLocalizedAttributesForm::FIELD_LOCALE_NAME => $localeName,
            ];
        }

        return [
            FileDirectoryForm::FIELD_NAME => $name,
            FileDirectoryForm::FIELD_FILE_DIRECTORY_LOCALIZED_ATTRIBUTES => $localizedAttributes,
        ];
    }

    /**
     * @return list<string>
     */
    protected function getLocaleNames(): array
    {
        return [static::LOCALE_NAME_ENGLISH, static::LOCALE_NAME_GERMAN];
    }

    protected function assertNameAndEveryTitleHaveErrors(FormInterface $fileDirectoryForm): void
    {
        $this->assertGreaterThan(0, $fileDirectoryForm->get(FileDirectoryForm::FIELD_NAME)->getErrors()->count());

        foreach ($fileDirectoryForm->get(FileDirectoryForm::FIELD_FILE_DIRECTORY_LOCALIZED_ATTRIBUTES) as $localizedAttributesForm) {
            $this->assertGreaterThan(0, $localizedAttributesForm->get(FileDirectoryLocalizedAttributesForm::FIELD_TITLE)->getErrors()->count());
        }
    }
}
