DROP VIEW IF EXISTS `$$VIEW_NAME$$`;

CREATE VIEW `$$VIEW_NAME$$` AS
SELECT
    project_id,
    project_name,
    group_id,
    group_name,
    date,
    search_engine,
    competitor,
    queries_count,
    ws2 as frequency2,
    top_10_count as top10_count,
    top10_percent,
    ws2_top10_count as frequency2_top10_count,
    ws2_top10_prc as frequency2_top10_percent,
    p_traf_count as potential_traffic_count,
    p_traf_prc as potential_traffic_percent
FROM `$$TABLE_NAME$$`;
