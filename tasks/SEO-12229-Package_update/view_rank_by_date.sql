DROP VIEW IF EXISTS `ozontest.test_dataset.v_groups_by_projects`;

CREATE VIEW `ozontest.test_dataset.v_groups_by_projects` AS
WITH adapter AS (
    SELECT *, rank() OVER(PARTITION BY group_id, project_id ORDER BY created_at DESC) as state
    FROM `ozontest.test_dataset.groups_by_projects`
)
SELECT group_id, group_name, project_id
FROM adapter
WHERE state = 1
ORDER BY group_id, project_id;







CREATE VIEW `ozontest.Client_6.v_groups_by_projects` AS
WITH adapter AS (
    SELECT *, rank() OVER(PARTITION BY group_id, project_id ORDER BY created_at DESC) as state
    FROM `ozontest.Client_6.groups_by_projects_br`
)
SELECT group_id, group_name, project_id
FROM adapter
WHERE state = 1
ORDER BY group_id, project_id;
