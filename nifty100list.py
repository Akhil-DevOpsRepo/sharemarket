
import pandas as pd
import numpy as np
import time ,io
from datetime import datetime
import requests 
import pymysql
from sqlalchemy import create_engine
import zipfile
import urllib.request

## Enviornment Variables ##
stocks_volume_data_url='https://archives.nseindia.com/archives/equities/mto/MTO_'
nifty_100_stocks_url='https://archives.nseindia.com/content/indices/ind_nifty100list.csv'
nifty_100_stocks_detail_url='https://www1.nseindia.com/content/historical/EQUITIES/'
host='localhost'
user='sharemarket_user'
password='Akagak12@'.replace('@','%40')
database='sharemarket'
tablename= 'volumedata'

def main():
    print("You are in main method")
    #create_table()
    df_total=fetch_volumes(stocks_volume_data_url)
    df_top100=fetch_nifty_100_stocks(nifty_100_stocks_url)
    df_top100_details=fetch_nifty_100_stocks_details(nifty_100_stocks_detail_url,df_top100)
    filtered_df = filter_nifty100_stocks(df_total,df_top100)
    complete_details = merge_priceindex_volumes(df_top100_details,filtered_df)
    
    #insert_data_todb(filtered_df)

def fetch_volumes(url):
    print("****************************************************")
    print("STEP1 : Fetching volume data of stocks from NSE")
    print("----------------------------------------------------")
    todays_date = datetime.today().strftime('%d%m%Y')
    url = url + todays_date +'.DAT'
    try:
        s = requests.get(url).content
        lines= s.splitlines()
        s=b'\n'.join(lines[4:])
        Column_names= b'extra,Sr,Symbol,Type,Volume,Delivery,Delivery-percentage\n'
        new_data= Column_names + s
        df1 = pd.read_csv(io.StringIO(new_data.decode('utf-8')))
        df1 =df1.drop(df1.columns[0],axis=1)
        #print(df1.head(2))
        print("Completed")
        print("****************************************************")
        return df1
    except Exception as e:
        print(e)
        return 0

def fetch_nifty_100_stocks(url):
    print("STEP2 : Fetching name of nifty 100 stocks from")
    print("----------------------------------------------------")
    s = requests.get(url).content
    df2 = pd.read_csv(io.StringIO(s.decode('utf-8')))
    print("Completed")
    print("****************************************************")
    return df2
def fetch_nifty_100_stocks_details(url,df_top100):
    print("STEP3 : Fetching details of nifty 100 stocks from NSE")
    print("----------------------------------------------------")
    current_month = (datetime.today().strftime('%b')).upper()
    current_year = (datetime.today().strftime('%Y'))
    current_date = datetime.today().strftime('%d')
    s= current_year+'/'+current_month+'/cm'+current_date+current_month+current_year+"bhav.csv.zip"
    #url = url+ "2023/MAR/cm27MAR2023bhav.csv.zip"
    url = url+ s
    urllib.request.urlretrieve(url, 'data.zip')
    with zipfile.ZipFile('data.zip', 'r') as zip_ref:
        zip_ref.extractall()
    df = pd.read_csv('cm27MAR2023bhav.csv')
    merged_df=pd.merge(df,df_top100,left_on='SYMBOL',right_on='Symbol')
    merged_df = merged_df[merged_df['SERIES']=='EQ']
    merged_df=merged_df[['SYMBOL','OPEN', 'HIGH', 'LOW', 'CLOSE']]
    #print(merged_df.shape)
    #print(merged_df.columns)
    #print(merged_df.head(1))
    print("Completed")
    print("****************************************************")
    return merged_df
def filter_nifty100_stocks(df1,df2):
    print("STEP4 : Filtering volume data of Nifty100 stocks")
    print("----------------------------------------------------")
    
    
    merged_df=pd.merge(df1,df2,on='Symbol')
    filtered_df = merged_df[['Symbol','Type','Volume','Delivery','Delivery-percentage']]
    filtered_df = filtered_df[filtered_df['Type']=='EQ']
    
    #print(filtered_df.head(2))
    print("Completed")
    print("****************************************************")
    return filtered_df

def merge_priceindex_volumes(df_top100_details,filtered_df):
    print("STEP5 : Merging volume and price data of Nifty100 stocks")
    print("----------------------------------------------------")
    todays_date = datetime.today().strftime('%d-%m-%Y')
    merged_df=pd.merge(df_top100_details,filtered_df,left_on='SYMBOL',right_on='Symbol')
    merged_df.insert(0,'Date',todays_date)
    merged_df=merged_df.drop(columns=['Symbol','Type'])
    print("Completed")
    print("****************************************************")
    return merged_df

def insert_data_todb(filtered_df):
    print("STEP6 : Inserting Data to SQL database")
    print("----------------------------------------------------")
    s='mysql+pymysql://{}:{}@localhost/{}'.format(user,password,database)
    engine = create_engine(s)
    filtered_df.to_sql(tablename, con = engine, if_exists = 'replace', chunksize = 1000,index=False)
    print("Completed")
    print("****************************************************")

def create_table():
    # Establish a connection to the MySQL database
    conn = pymysql.connect(host=host ,user=user ,password='Akagak12@' ,database=database)

    # Create a cursor object
    cursor = conn.cursor()

    # Create a SQL query to create a new table
    query = "CREATE TABLE volumedata ( Type VARCHAR(255), Symbol VARCHAR(255), Volume INT, Delivery INT, `Delivery-percentage` FLOAT)"

    # Execute the SQL query
    cursor.execute(query)

if __name__ == '__main__':
    main()