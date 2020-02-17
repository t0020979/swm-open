DROP VIEW IF EXISTS `ozontest.Client_6.v_projects_by_groups`;

CREATE VIEW `ozontest.Client_6.v_projects_by_groups` AS

SELECT group_id, group_name, project_id FROM `ozontest.Client_6.projects_by_groups` 
