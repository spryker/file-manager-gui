<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerGui\Dependency\Facade;

use Generated\Shared\Transfer\FileDirectoryTreeTransfer;
use Generated\Shared\Transfer\FileManagerSaveRequestTransfer;
use Generated\Shared\Transfer\LocaleTransfer;

interface FileManagerGuiToFileManagerFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\FileManagerSaveRequestTransfer $saveRequestTransfer
     *
     * @return int
     */
    public function save(FileManagerSaveRequestTransfer $saveRequestTransfer);

    /**
     * @api
     *
     * @param int $idFile
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemReadException
     *
     * @return \Generated\Shared\Transfer\FileManagerReadResponseTransfer
     */
    public function readLatestFileVersion($idFile);

    /**
     * @api
     *
     * @param int $idFile
     *
     * @return bool
     */
    public function delete($idFile);

    /**
     * @api
     *
     * @param int $idFileInfo
     *
     * @return bool
     */
    public function deleteFileInfo($idFileInfo);

    /**
     * @api
     *
     * @param int $idFile
     * @param int $idFileInfo
     *
     * @return void
     */
    public function rollback($idFile, $idFileInfo);

    /**
     * @api
     *
     * @param int $idFileInfo
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemReadException
     *
     * @return \Generated\Shared\Transfer\FileManagerReadResponseTransfer
     */
    public function read($idFileInfo);

    /**
     * Specification:
     * - Finds a file info
     * - Returns a file meta info and a file content with a specified version
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemReadException
     *
     * @return \Generated\Shared\Transfer\FileManagerReadResponseTransfer
     */
    public function findFileDirectoryTree(LocaleTransfer $localeTransfer = null);

    /**
     * Specification:
     * - Finds a file info
     * - Returns a file meta info and a file content with a specified version
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileDirectoryTreeTransfer $fileDirectoryTreeTransfer
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemReadException
     *
     * @return \Generated\Shared\Transfer\FileManagerReadResponseTransfer
     */
    public function updateFileDirectoryTreeHierarchy(FileDirectoryTreeTransfer $fileDirectoryTreeTransfer);
}
