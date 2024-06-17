# New Directions
Phase 2 Test for PHP API

# Task
You have been tasked with building a system to allow staff at New Directions (ND) to be able to view potential applicants based on: - 
 - County 
 - Whether they require a DBS check 
 - Position applied for 

You are to build a REST API using PHP that is to be consumed by a website used by the company, which allows staff to query and view the applicant's data & download their relevant CVs.  

You have also heard that ND might be looking to make the API available to third parties to use as well. 

How can we ensure that companies can only see their own applicants? Also, thinking about security, how can we ensure that the API can only be accessed by authorised companies? 

This challenge should cover core disciplines. 

Back-end focus: Using the sample dataset, build an API that can be used by front-end applications 
Front-end focus: Use the API to build a front-end application to present this dataset in a searchable manner to end users 

## FRONT END LIVE DEMO: 
https://3dmintlab.com

## Notes:

### Database
To allow for easy data manipulation and in the interest of building a scaleable solution, the data has been inserted into a PostGreSQL database (jobs.pgsql) with the following tables:
```
vendors (
 id SERIAL PRIMARY KEY,
 name VARCHAR(255) NOT NULL UNIQUE
)

api_keys (
 id SERIAL PRIMARY KEY,
 vendor_id INT REFERENCES vendors(id),
 api_key VARCHAR(255) NOT NULL UNIQUE
)

applicants (
 id SERIAL PRIMARY KEY,
 name VARCHAR(255),
 email VARCHAR(255),
 phone VARCHAR(50),
 vendor_id INT REFERENCES vendors(id),
 address1 TEXT,
 county VARCHAR(255),
 country VARCHAR(255),
 post_code VARCHAR(50),
 require_dbs_check BOOLEAN,
 applied_for VARCHAR(255),
 cv TEXT
)
```
### Files:
index.php contains all front-end code, including the Javascript.

api.php is the Endpoint for the API.

'API_KEY' must be passed via the url. 

Each API key is linked to a Company via the 'api_keys' and 'vendors' Tables. 
Each Applicant is linked to a Company in the 'applicants' Table.

Companies can only see data relavant to them.
Multiple API Keys can be given out and managed for each company.

An Admin API key is present to allow searching for all Applicants for all companies.

The endpoint also has the following optional paramaters to filter results.
```
'county' | Varchar
'dbsRequired' | Boolean
'appliedFor' | Varchar
```
### Front-End Example
https://3dmintlab.com

### API Examples
Bad Key Example: https://3dmintlab.com/api.php?API-KEY=WRONGKEY

Admin Example: https://3dmintlab.com/api.php?API-KEY=MASTERKEY001&appliedFor=Kitten+Cuddler&dbsRequired=False

New Directions company Example: https://3dmintlab.com/api.php?API-KEY=NEWDIRECTIONS_KEY001&dbsRequired=True
