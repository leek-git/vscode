# Mysql基础知识

### 一、sql的概念

结构化查询语言(Structured Query Language)简称SQL，是一种特殊目的的编程语言，是一种数据库查询和程序设计语言，用于存取数据以及查询、更新和管理关系数据库系统。

### 二、sql的分类

1. 数据定义语言（Data Definition Language） DDL
- 用来定义数据库对象：数据库，表，列……
```sql
1.创建数据库
	create database 数据库名； 
	create database 数据库名 character set 字符集； //字符集一般为uft-8，第二个语句是创建数据的同时并设置了字符集。
2.查看数据库
	use 数据库名;   //切换数据库
	show databases;  //查看MySQL中都有哪些数据库
	select database();   //查看当前正在使用的数据库
	show create database 数据库名；  //查看一个数据库的定义信息
3.修改数据库字符集
	alter database 数据库名 character set 字符集;
4.删除数据库
	drop database 数据库名;
5.创建表
	CREATE TABLE 表名(
		字段名称1  字段类型（长度）,
		字段名称2  字段类型（长度）
		//注意：最后一个字段名称末尾不加,
    );
	/*常用的数据类型
	  int  整型  double 浮点型  varchar 字符串 
	  data 	日期类型，yyyy-MM-dd  年-月-日
	  注意： char也表示字符串，但是跟varchar是有区别的
	  char和varchar区别：
	   	 char类型是固定长度的，varchar是根据输入字符分配合适的空间，一般情况下用varchar
	 */
6.查看表
	show tables;   //查看当前数据库所有的表
	desc 表名;  //查看表的结构
7.修改表
	rename table 旧表名 to 新表名;  // 修改表名
	alter table 表名 character set 字符集;  //修改字符集
	alert table 表名 add 字段名称 字段类型;  //向表中添加字段
	alter table 表名 drop 字段名;  //删除字段
8.删除表
	drop 表名 if exists 表名; //删除该表

```
2. 数据操作语言（Data Manipulation Language） DML
- 用来对数据库中表的记录进行更新。
```sql
1.插入数据
	insert into 表名 （字段名1，字段名2...） values(字段值1，字段值2...);
2.更改数据
	update 表名 set 列名 = 值 [where 字段名 = 值];
3.删除数据
	delete from 表名 [where 字段名 = 值];
	
```
3. 数据查询语言（Data Query Language） DQL
- 用来查询数据库中表的记录。
```sql
1.简单查询
	select 列名 from 表名; //列名可以用*号代替表示查询所有字段
2.条件查询
	select 列名 from 表名 where 条件表达式;
	//  %表示任意多个字符串，  _表示匹配一个字符，
3.排序
	SELECT 字段名 FROM 表名 [WHERE 字段 = 值] ORDER BY 字段名 [ASC / DESC];
	// ASC 表示升序排序(默认)，DESC表示降序排序
4.聚合函数
	/*常用聚合函数
	* count(字段) 统计指定列不为NULL的记录行数
	* sum(字段)  计算指定列的数值和
	* max(字段)  计算指定列的最大值
	* min(字段)  计算指定列的最小值
	* avg(字段)  计算指定列的平均值
	*/
	SELECT 聚合函数(字段名) FROM 表名;
5.分组
	SELECT 分组字段/聚合函数 FROM 表名 GROUP BY 分组字段 [HAVING 条件];
6.limit关键字
	SELECT 字段1,字段2... FROM 表名 LIMIT offset , length;
	// offset  起始行数, 从0开始记数, 如果省略 则默认为 0.
	// length 返回的行数

```
4. 数据控制语言（Data Control Language） DCL
- 
```sql
1.创建用户
	CREATE USER '用户名'@'主机名' IDENTIFIED BY '密码';
2.给用户授权
	GRANT 权限 1, 权限 2... ON 数据库名.表名 TO '用户名'@'主机名';
3.查看权限
	SHOW GRANTS FOR '用户名'@'主机名';
4.删除用户
	DROP USER '用户名'@'主机名';
5.查询用户
	SELECT * FROM USER;
	
```
### 三、sql约束
```sql
1. 主键约束
	// 不可重复 唯一 非空
	字段名 字段类型 primary key；
	//  主键的自动增长 AUTO_INCREMENT 表示自动增长(字段类型必须是整数类型)
	// 默认主键起始值是1，改变起始值
	//-- 创建主键自增的表,自定义自增其实值字段名 字段类型 DEFAULT 默认值
	
	// CREATE TABLE 表名(
	// eid INT PRIMARY KEY AUTO_INCREMENT,
	// ……
	// )AUTO_INCREMENT=100;

	// DELETE和TRUNCATE对自增长的影响（前者对自增没有影响，后者从1开始自增）
2. 非空约束
	字段名 字段类型 not null,
3. 唯一约束
	字段名 字段类型 unique,
4. 默认值
	字段名 字段类型 DEFAULT 默认值 
```
### 四、事务控制
```sql
1.什么是事务
	事务是一个整体,由一条或者多条SQL 语句组成,这些SQL语句要么都执行成功,要么都执行失败, 只要一条SQL出现异常,整个操作就会回滚,整个业务执行失败。
2. 手动提交事务
	① 开启事务 start transaction; 
	② 提交事务 commit;
	③ 回滚事务 rollback;
3. 自动提交事务（MySQL默认）
	// 取消自动提交
	SET @@autocommit=off;
	// 查看自动状态， on是自动，off是手动提交
	SHOW VARIABLES LIKE 'autocommit';
4. **事务的四大特性**（重点）
	原子性，一致性，隔离性，持久性。
5. 事务隔离级别
	//查看隔离级别
	select @@tx_isolation; //5.7版本
	// 设置隔离级别
	set global transaction isolation level 级别名称;
	// read uncommitted 读未提交
	// read committed 读已提交
	// repeatable read 可重复读
	// serializable 串行化

```
### 五、多表
- ***概述***
1. 在实际开发中，单表是满足不了业务需求的，同一个字段中很可能出现大量的冗余字段，这就需要我们引入了多表。
2. 如果表1的某个字段与表2的主键对应，那么表1的这个字段就称为表1的外键，拥有外键的表是从表，与外键对应的主键所在的表成为主表。
3. 外键约束：外键约束可以让两张表之间产生一定的对应关系，从而形成了约束。
4. 外键指的是与在从表中与主表的主键对应的字段。
5. 添加外键约束的语法格式
```sql
    [CONSTRAINT] [外键约束名称] FOREIGN KEY(外键字段名) REFERENCES 
    主表名(主键字段名);
    alter table 从表 add [CONSTRAINT] [外键约束名称] FOREIGN KEY(外键字段名) 		REFERENCES 主表名(主键字段名);
    //  中括号里面的东西都是可以省略的 
```
6. 删除外键约束的语法格式
```sql
    alter table 从表 drop foreign key 外键约束名称;
```
7. 外键约束的注意事项：
    ① 从表的外键类型必须与主表的主键类型一致。
    ② 添加数据时，应该先添加主表的数据，然后再添加从表的数据
    ③ 删除数据的时候，要先删除从表中的数据，再删除主表中的数据。
8. 级联删除
    删除数据的时候，要先删除从表中的数据，再删除主表中的数据，设置级联删除便可以直接删除主表中的数据，同时从表中的数据也会跟着消失。
```sql
    on delete cascade; //添加外键约束的时候后面跟着这句
```
- ***多表间关系***
1. 一对多： 在多的表上建立外键
2. 多对多： 建立第三个表，最起码有两个字段（两个表的主键）
3. 一对一： 任意一个表上建立外键

- ***多表查询***
1. 内连接查询
```sql
SELECT 字段名 FROM 左表, 右表 WHERE 连接条件;
SELECT 字段名 FROM 左表 [inner]  JOIN 右表 ON 连接条件;
```
2. 外连接查询
```sql
SELECT 字段名 FROM 坐标 LEFT [outer] JOIN 右表 ON 连接条件;
SELECT 字段名 FROM 坐标 RIGHT [outer] JOIN 右表 ON 连接条件;
// 内连接: inner join , 只获取两张表中 交集部分的数据.
// 左外连接: left join , 以左表为基准 ,查询左表的所有数据, 以及与右表有交集的部分
// 右外连接: right join , 以右表为基准,查询右表的所有的数据,以及与左表有交集的部分
```
3. 子查询
```sql
SELECT 查询字段 FROM 表 WHERE 字段=（子查询）;
SELECT 查询字段 FROM （子查询）表别名 WHERE 条件;
SELECT 查询字段 FROM 表 WHERE 字段 IN （子查询）;
// 子查询如果查出的是一个字段(单列), 那就在where后面作为条件使用.
// 子查询如果查询出的是多个字段(多列), 就当做一张表使用(要起别名).
```

### 六、数据库三范式
- ***数据库设计的一种规则***
目的： 创建 冗余较小，结构合理的数据库。
第一范式（1NF） 满足最低要求的范式——列具有原子性，列要做到不可拆分性
第二范式（2NF） 在满足第一范式的基础之上进一步满足更多的规范—— 一张表只能描述一件事情
第三范式（3NF） 以此类推……

### 七、MySQL索引
索引的作用：为了提高索引的效率

**常见索引的分类:** 主键索引（primary key）；
唯一索引(unique): 普通索引（index）；

***主键索引***
```sql
1.创建的时候添加主键索引
	CREATE TABLE 表名(
	字段名 类型 PRIMARY KEY ,
	//主键索引是唯一索引
	);
2.在已有表的基础上添加主键索引
	ALTER TABLE 表名 ADD PRIMARY KEY(列名);
```
1. 唯一索引
```sql
1. 创建的时候添加唯一索引
	CREATE TABLE 表名(
		UNIQUE [索引名称] (列名) 
	);
2. 在已有表的基础上添加唯一索引
	CREATE UNIQUE INDEX 索引名 on 表名(列名);
	ALTER TABLE 表名 ADD UNIQUE (列名);
```
2. 普通索引
```sql
	create index 索引名 on 表名(列名[长度])；
	ALTER TABLE 表名 ADD INDEX 索引名(列名);
```
3. 删除索引
```sql
	ALTER TABLE table_name DROP INDEX index_name;
```
### 八、MySQL视图
```sql
// 视图是一个表中根据不同需求提取出来的一个实际上不存在的表
create view 视图名 [column_list] as select语句;
// 通过视图进行查询时将视图看成是一张表即可
```
    





