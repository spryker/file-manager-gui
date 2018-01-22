<?php

namespace Spryker\Zed\FileManagerGui\Communication;

use Spryker\Zed\FileManager\Business\FileManagerFacadeInterface;
use Spryker\Zed\FileManager\Persistence\FileManagerQueryContainer;
use Spryker\Zed\FileManagerGui\Communication\Form\DataProvider\FileFormDataProvider;
use Spryker\Zed\FileManagerGui\Communication\Form\FileForm;
use Spryker\Zed\FileManagerGui\Communication\Form\Tabs\FileFormTabs;
use Spryker\Zed\FileManagerGui\Communication\Table\FileTable;
use Spryker\Zed\FileManagerGui\Communication\Table\FileInfoTable;
use Spryker\Zed\FileManagerGui\FileManagerGuiDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Locale\Business\LocaleFacadeInterface;

class FileManagerGuiCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return FileTable
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function createFileTable()
    {
        return new FileTable(
            $this->getFileManagerQueryContainer()
        );
    }

    /**
     * @param int $idFile
     * @return FileInfoTable
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function createFileInfoTable(int $idFile)
    {
        return new FileInfoTable(
            $this->getFileManagerQueryContainer(),
            $idFile
        );
    }

    /**
     * @param array $formData
     * @param array $formOptions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createFileForm(array $formData = [], array $formOptions = [])
    {
        $fileForm = new FileForm();

        return $this->getFormFactory()->create($fileForm, $formData, $formOptions);
    }

    /**
     * @return FileFormDataProvider
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function createFileFormDataProvider()
    {
        return new FileFormDataProvider($this->getFileManagerQueryContainer());
    }

    /**
     * @return FileFormTabs
     */
    public function createFileFormTabs()
    {
        return new FileFormTabs();
    }

    /**
     * @return FileManagerFacadeInterface
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function getFileManagerFacade()
    {
        return $this->getProvidedDependency(FileManagerGuiDependencyProvider::FACADE_FILE_MANAGER);
    }

    /**
     * @return FileManagerQueryContainer
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    protected function getFileManagerQueryContainer()
    {
        return $this->getProvidedDependency(FileManagerGuiDependencyProvider::QUERY_CONTAINER_FILE_MANAGER);
    }

    /**
     * @return LocaleFacadeInterface
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function getLocaleFacade()
    {
        return $this->getProvidedDependency(FileManagerGuiDependencyProvider::FACADE_LOCALE);
    }

}