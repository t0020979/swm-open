
-- https://stackoverflow.com/questions/43085896/update-or-delete-tables-with-streaming-buffer-in-bigquery
-- You probably have to wait up to 90 minutes so all buffer is persisted on the cluster.
-- Проблема удаления данных
-- -- -- -- -- -- -- --

-- Удалять можно, когда streamingBuffer пропадает
-- var_dump($this->porter->table()->info()['streamingBuffer']);
-- var_dump( date( 'Y-m-d H:i:s', intval($this->porter->table()->info()['streamingBuffer']['oldestEntryTime'])/1000) );


DELETE FROM `ozontest.test_dataset.groups_by_projects` a
WHERE NOT EXISTS
    (
      WITH ranked_adapter AS (
          SELECT *, rank() OVER(PARTITION BY group_id, project_id ORDER BY created_at DESC) as state
          FROM `ozontest.test_dataset.groups_by_projects`
      ), adapter AS (
          SELECT group_id, project_id, created_at
          FROM ranked_adapter
          WHERE state = 1
      )
        SELECT * FROM adapter b
        WHERE a.group_id = b.group_id AND a.project_id = b.project_id AND a.created_at = b.created_at
    )
