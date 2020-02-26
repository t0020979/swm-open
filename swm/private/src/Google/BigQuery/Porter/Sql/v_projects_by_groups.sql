DROP VIEW IF EXISTS `$$VIEW_NAME$$`;

CREATE VIEW `$$VIEW_NAME$$` AS
WITH adapter AS (
    SELECT *, rank() OVER(PARTITION BY group_id, project_id ORDER BY created_at DESC) as state
    FROM `$$TABLE_NAME$$`
)
SELECT group_id, group_name, project_id
FROM adapter
WHERE state = 1
ORDER BY group_id, project_id;
