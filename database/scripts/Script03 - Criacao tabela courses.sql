USE university_market_db;
create table courses (
	id int not null auto_increment primary key,
	course_name varchar(50) not null,
	grid_cols numeric,
	grid_rows numeric,
	img_path varchar(150)
)