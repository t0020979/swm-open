DROP VIEW IF EXISTS `ozontest.Client_6.w_avg_group_semantics_dashboard`;

CREATE VIEW `ozontest.Client_6.w_avg_group_semantics_dashboard` AS
-- адаптер исходных данных, и защита от деления на 0
WITH adapter AS (
    SELECT sd.project_id, project_name, group_id, group_name, search_engine, date,
      queries_count, frequency2,
      top3_count, top5_count, top10_count, top100_count,
      frequency2_top10_count, potential_traffic_count
    FROM `ozontest.Client_6.v_semantics_dashboard` as sd
    RIGHT JOIN `ozontest.Client_6.v_projects_by_groups` USING (project_id)
    WHERE frequency2 > 0 AND queries_count > 0
)

-- проверка на уникальные строки по ключевым полям (исключаем дублирующие ключевые значения)
, unique_keys AS (
    SELECT project_id, group_id, search_engine, date, count(*) as `rows`
    FROM adapter
    GROUP BY project_id, group_id, search_engine, date
    HAVING `rows` = 1
    ORDER BY group_id, date DESC, project_id, search_engine
)

-- по каждому набору ключей выбираем кол-во проектов
, keys_with_projects AS (
    SELECT group_id, search_engine, date, count(*) as projects
    FROM unique_keys
    GROUP BY group_id, search_engine, date
    ORDER BY projects DESC, date DESC, group_id, search_engine
)

-- максимальное кол-во проектов среди дат по каждому набору ключей
, max_projects_by_date AS (
    SELECT group_id, search_engine, max(projects) as max_projects
    FROM keys_with_projects
    GROUP BY group_id, search_engine
    ORDER BY group_id, search_engine
)

-- наборы ключей, пригодные для использования по максимальному числу проектов
, useful_keys AS (
    SELECT kwp.*
    FROM keys_with_projects as kwp
    LEFT JOIN max_projects_by_date as mpd USING(group_id, search_engine)
    WHERE kwp.projects = mpd.max_projects
    ORDER BY projects DESC
)

-- подготовленные данные для усреднения среди проектов
, prepared_data AS (
  SELECT uk.group_id, uk.search_engine, uk.date, project_id,
    group_name,
    queries_count, frequency2,
    top3_count, top5_count, top10_count, top100_count,
    frequency2_top10_count, potential_traffic_count
  FROM useful_keys as uk
  RIGHT JOIN adapter USING (group_id, search_engine, date )
  WHERE uk.group_id IS NOT NULL AND uk.search_engine IS NOT NULL AND uk.date IS NOT NULL
  ORDER BY group_id, search_engine, date
)

SELECT
    group_id, group_name, date, search_engine, count(*) as projects,

    ROUND(  100 *   sum(top3_count) / sum(queries_count), 2) as avg_top3_prc,
    ROUND(  100 *   sum(top5_count) / sum(queries_count), 2) as avg_top5_prc,
    ROUND(  100 *  sum(top10_count) / sum(queries_count), 2) as avg_top10_prc,
    ROUND(  100 * sum(top100_count) / sum(queries_count), 2) as avg_top100_prc,
    ROUND(  100 * sum(frequency2_top10_count) / sum(frequency2), 2) as avg_ws2_top10_prc,
    ROUND( 1000 * sum(potential_traffic_count) / sum(frequency2) , 2) as avg_p_traf_prc,
FROM prepared_data
GROUP BY
    group_id, date, search_engine, group_name
ORDER BY
    date DESC, group_id, search_engine
;
