select distinct concat("'",FixedVersion,"'",","),concat("'",ReleasedDate,"'",",") from dbs_qaqueue where DB_Status='Released' and fixedversion <> 'null' and FixedVersion <>'' and ReleasedDate<>'0000-00-00'
and ReleasedDate between '2020-01-01' and '2020-01-31' and (UpdatedBy='sherzig@cleverdevices.com' OR  UpdatedBy='psingh@cleverdevices.com')
order by FixedVersion;

select * from dbs_qaqueue where fixedversion like '%VTA%'

delete from dbs_qaqueue where queuID=390

select b.fname,b.lname,count(*) from dbs_customerbase a 
inner join dbs_users b on a.developer_assigned=b.userid
group by b.fname,b.lname


select * from dbs_customerbase where developer_assigned=13

select * from dbs_customerbase a 
inner join dbs_users b on a.developer_assigned=b.userid
group by a.fname,a.lname

order by customeriD


insert into dbs_customerbase values (118,'SUNLINE','12')
select * from dbs_users


select * from session order by createtime

select * from dbs_qaqueue where fixedVersion like '%san%'

select * from track_status where QueueID=415 order by createtime

select * from dbs_customerbase where customername like '%PAAC%'



select  
FixedVersion as 'ReleaseName',
Actination_Date as 'DB Activation Date',
DB_ReleaseDate as 'Project Team Asked Date',
ReleasedDate as 'Database Pinned Date',
datediff(Actination_Date,ReleasedDate) as 'Difference between Pinned and Activation',
datediff(ReleasedDate,DB_ReleaseDate) as 'Difference between Pinned and Ask date'
 from dbs_qaqueue where Actination_date between '2019-10-01' and '2020-03-31' and DB_Status='Released' 
 order by ReleasedDate
 
 
INSERT INTO dbs_customerdetails (Customer, hastransfers, hasPSAs, hasScheudle, hasbustime, hasCAD, hasFareBox, hasCanneddate, hasgeofences, hasTSPs, hasTCPs, hasPrePosttrips, hasheadways, hasmanualrotues, hasLBAs, hasstoprequested, hasotherlanguage, hasTurnWarning, numberofroutes, numberoftestrotues, numberofstops, numberofdepots, numberofhubs, destint, Globalsdocs, multileveldest, createddate, createdby, Status, texttospeech, cleverware, Busware) VALUES (89, 1, 1, 1, 0, 0, 1, 1, 1, 0, 0, 1, 1, 1, 0, 0, 0, 1, 1, 1, 1, 1, 1, 0, 1, 0, now(), 'pvirdi@cleverdevices.com', 1, 0, 1, 0)

select * from dbs_customerdetails

select * from dbs_customerbase where customername ='Task'

update dbs_customerbase set developer_assigned=2 where customername ='Task'

select * from dbs_users

select * from dbs_qaqueue where fixedversion='PTD_Apr2020_Rev1'  and DB_Status='Ready QA'
delete from dbs_qaqueue where fixedversion='PTD_Apr2020_Rev1'  and DB_Status='Ready QA'

select fixedversion,releaseddate,exportNumber,pick from dbs_qaqueue where   releaseddate between '2019-09-01' and  '2019-12-31'


INSERT INTO dbs_customerdetails (Customer, hastransfers, hasPSAs, hasScheudle, hasbustime, hasCAD, hasFareBox, hasCanneddate, hasgeofences, hasTSPs, hasTCPs, hasPrePosttrips, hasheadways, hasmanualrotues, hasLBAs, hasstoprequested, hasotherlanguage, hasTurnWarning, numberofroutes, numberoftestrotues, numberofstops, numberofdepots, numberofhubs, destint, Globalsdocs, multileveldest, createddate, createdby, Status, texttospeech, cleverware, Busware) VALUES (43, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, , , , , , 0, 1, 0, now(), 'pvirdi@cleverdevices.com', 1, 0, 0, 1)