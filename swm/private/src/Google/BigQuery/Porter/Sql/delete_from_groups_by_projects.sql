DELETE FROM `$$TABLE_NAME$$` a
WHERE NOT EXISTS
(
    WITH ranked_adapter AS (
        SELECT *, rank() OVER(PARTITION BY group_id, project_id ORDER BY created_at DESC) as state
        FROM `$$TABLE_NAME$$`
    ), adapter AS (
        SELECT group_id, project_id, created_at
        FROM ranked_adapter
        WHERE state = 1
    )
    SELECT * FROM adapter b
    WHERE a.group_id = b.group_id AND a.project_id = b.project_id AND a.created_at = b.created_at
)
