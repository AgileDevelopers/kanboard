<?php

namespace Kanboard\Model;

/**
 * Task File Model
 *
 * @package  Kanboard\Model
 * @author   Frederic Guillot
 */
class TaskFileModel extends FileModel
{
    /**
     * Table name
     *
     * @var string
     */
    const TABLE = 'task_has_files';

    /**
     * Events
     *
     * @var string
     */
    const EVENT_CREATE = 'task.file.create';

    /**
     * Get the table
     *
     * @abstract
     * @access protected
     * @return string
     */
    protected function getTable()
    {
        return self::TABLE;
    }

    /**
     * Define the foreign key
     *
     * @abstract
     * @access protected
     * @return string
     */
    protected function getForeignKey()
    {
        return 'task_id';
    }

    /**
     * Define the path prefix
     *
     * @abstract
     * @access protected
     * @return string
     */
    protected function getPathPrefix()
    {
        return 'tasks';
    }

    /**
     * Get event name
     *
     * @abstract
     * @access protected
     * @return string
     */
    protected function getEventName()
    {
        return self::EVENT_CREATE;
    }

    /**
     * Get projectId from fileId
     *
     * @access public
     * @param  integer $file_id
     * @return integer
     */
    public function getProjectId($file_id)
    {
        return $this->db
            ->table(self::TABLE)
            ->eq(self::TABLE.'.id', $file_id)
            ->join(TaskModel::TABLE, 'id', 'task_id')
            ->findOneColumn(TaskModel::TABLE . '.project_id') ?: 0;
    }

    /**
     * Handle screenshot upload
     *
     * @access public
     * @param  integer  $task_id      Task id
     * @param  string   $blob         Base64 encoded image
     * @return bool|integer
     */
    public function uploadScreenshot($task_id, $blob)
    {
        $original_filename = e('Screenshot taken %s', $this->helper->dt->datetime(time())).'.png';
        return $this->uploadContent($task_id, $original_filename, $blob);
    }
}
