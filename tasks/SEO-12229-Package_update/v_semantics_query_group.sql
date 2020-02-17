DROP VIEW IF EXISTS `ozontest.Client_6.v_semantics_query_group`;

CREATE VIEW `ozontest.Client_6.v_semantics_query_group` AS
SELECT
    project_id,
    project_name,
    group_id,
    group_name,
    date,
    search_engine,
    queries_count,
    documents_count,
    frequency2,
    top3_count,
    top5_count,
    top10_count,
    top100_count,
    top3_percent,
    top5_percent,
    top10_percent,
    top100_percent,
    frequency2_top10_count,
    frequency2_top10_percent,
    potential_traffic	as potential_traffic_count,
    potential_traffic_percent
FROM `ozontest.Client_6.semantics_query_group`;
