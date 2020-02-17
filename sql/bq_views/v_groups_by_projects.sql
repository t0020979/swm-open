CREATE VIEW `ozontest.test_dataset.v_groups_by_projects` AS
WITH
ranked_adapter AS (
    SELECT *, rank() OVER(PARTITION BY group_id, project_id ORDER BY created_at DESC) as rank_dated
    FROM `ozontest.test_dataset.groups_by_projects`
)
SELECT group_id, group_name, project_id
FROM ranked_adapter
WHERE rank_dated = 1
ORDER BY group_id, project_id
