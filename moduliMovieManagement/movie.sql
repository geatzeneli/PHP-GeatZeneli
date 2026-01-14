CREATE TABLE `users`(
`id` int(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
`emri` varchar(255) not null,
`username` varchar(255) not null,
`email` varchar(255) not null,
`password` varchar(255) not null,
`confirm_password` varchar(255) not null,
`is_admin` varchar(255) not null
);

CREATE TABLE `movies`(
`id` int(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
`movie_name` varchar(255) not null,
`movie_desc` varchar(255) not null,
`movie_quality` varchar(255) not null,
`movie_rating` varchar(255) not null,
`movie_image` varchar(255) not null

);

CREATE TABLE `bookings`(
`id` int(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
`user_id` int(11) not null,
`movie_id` int(11) not null,
`nr_tickets` int(20) not null,
`date` varchar(255) not null,
`time` varchar(255) not null

);