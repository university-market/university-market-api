USE university_market_db;
create table Sales(
	id INT not null auto_increment,
	title varchar(50) not null,
	description varchar(500),
	date_start date not null,
	date_end date not null,
	alternative_value numeric not null,
	status varchar(50) not null,
	user_id int not null,
	course_id int not null,
	primary KEY(id),
	foreign key(user_id) references users(id),
	foreign key(course_id) references courses(id)
)
