ALTER TABLE courses
    ADD COLUMN slider_one_json MEDIUMTEXT NULL AFTER image,
    ADD COLUMN slider_two_json MEDIUMTEXT NULL AFTER slider_one_json,
    ADD COLUMN feature_video_url VARCHAR(255) NULL AFTER slider_two_json,
    ADD COLUMN pdfs_json MEDIUMTEXT NULL AFTER feature_video_url,
    ADD COLUMN extra_youtube_json MEDIUMTEXT NULL AFTER pdfs_json;
