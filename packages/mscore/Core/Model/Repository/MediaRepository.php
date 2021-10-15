<?php

namespace MS\Core\Model\Repository;

use MS\Core\Model\Repositories;

/**
 * Class MediaRepository
 *
 * @package MS\Core\Model\Repository
 */
class MediaRepository extends Repositories
{
    protected static $instance = null;

    /**
     * Get instance of this Class
     *
     * @return MediaRepository
     */
    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param array $files
     * @param string $table
     * @param string $sourceTarget
     * @param int $patientId
     * @return bool
     */
    public function uploadSrcToDB($files = [], $sourceTarget = '', $patientId = -1, $sessionId = -1)
    {
        foreach ($files as $file) {
            $this->getQueryBuilder()
                ->insert()
                ->setTableName('ms_media')
                ->setColumnsAndValues(
                    [
                        'patientId' => (int)$patientId,
                        'sessionId' => (int)$sessionId,
                        'src' => $sourceTarget . $file['name'],
                    ]
                )
                ->execute()
            ;
        }

        return true;
    }
}
