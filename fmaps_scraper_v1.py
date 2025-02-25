#! /usr/bin/env python

# import the necessary packages
from seleniumwire import webdriver as wired
from selenium.webdriver.chrome.options import Options
from selenium.webdriver.common.by import By
from selenium.webdriver.chrome.service import Service
from webdriver_manager.chrome import ChromeDriverManager
from time import sleep
import requests
import json
import pprint
import datetime
import traceback
from sqlalchemy import create_engine


pp = pprint.PrettyPrinter(indent=2)
ser = Service(ChromeDriverManager().install())

chrome_options = Options()
chrome_options.add_argument("--headless")
driver = wired.Chrome(service=ser, options=chrome_options)


user = "datascrapping"
password = "Smixing4589##"
host = "68.178.195.121"
port = 3306
db = "datascrapping"


engine = create_engine(
    "mysql+pymysql://{0}:{1}@{2}:{3}/{4}".format(user, password, host, port, db)
)
conn = engine.raw_connection()
if conn.open:
    print("Connection to DB Successful")
else:
    print("Could not Connect to DB")
myCursor = conn.cursor()


def insert_data(val):
    try:
        sql = "INSERT IGNORE INTO scrapped_data (serial_number,property_address,consumer,need_by,amount,property_type,year,construction) VALUES (%s,%s,%s,%s,%s,%s,%s,%s)"
        # val = (1, "black",'fsdagsagsaifeiufiasav',233253)
        myCursor.execute(sql, val)
        conn.commit()
        print("Data Inserted")
        return True
    except:
        traceback.print_exc()


def get_element_by_xpath(driver, xpath):
    reqCount = 0
    while True:
        if reqCount > 1000:
            break
        try:
            elem = driver.find_element(By.XPATH, xpath)
            if elem:
                return elem
        except:
            reqCount += 1
            pass


def click_elem(elem):
    reqCount = 0
    while True:
        if reqCount > 1000:
            break
        try:
            elem.click()
        except:
            reqCount += 1
            pass


def get_headers(request_url):
    menu_btn_xpath = '//*[@id="root"]/div/div[1]/header/div/button[4]'
    login_btn_xpath = "/html/body/div[2]/div[3]/div/ul/div[2]/div/span"
    username_input_xpath = '//*[@id="j_username"]'
    password_input_xpath = '//*[@id="j_password"]'
    submit_btn_xpath = '//*[@id="loginForm"]/div/input'

    fmap_username = "ccole4"
    fmap_password = "Amanda2022$"

    base_url = "https://fmap.citizensfla.com/"

    driver.get(base_url)

    get_element_by_xpath(driver, menu_btn_xpath).click()
    sleep(1)
    get_element_by_xpath(driver, login_btn_xpath).click()
    sleep(2)
    get_element_by_xpath(driver, username_input_xpath).send_keys(fmap_username)
    get_element_by_xpath(driver, password_input_xpath).send_keys(fmap_password)
    sleep(1)
    get_element_by_xpath(driver, submit_btn_xpath).click()

    print("Please Wait ...")
    sleep(3)
    myHeaders = {}
    for request in driver.requests:
        if request.url == request_url:
            print("Headers Found !")
            myHeaders = request.headers
            break
    # driver.quit()
    return myHeaders


def get_response(url, req_type, headers={}, payload={}):
    reqCount = 0
    while True:
        if reqCount > 10:
            break
        try:
            reqCount += 1
            response = requests.request(
                req_type.upper(), url, headers=headers, data=payload
            )
            if response.status_code == 200:
                return response
            else:
                continue
        except:
            # traceback.print_exc()
            pass


def tag_lead(headers, lead_agent_map, lead, agent):
    url = "https://fmap.citizensfla.com/fmap/lead-agent/update-lead-agent-map-status/TAGGED"

    payload = json.dumps(
        [
            {
                "leadAgentMapId": lead_agent_map,
                "leadId": lead,
                "quoteStatus": "TAGGED",
                "agentId": agent,
            }
        ]
    )

    resp = get_response(url, "POST", headers=headers, payload=payload)

    print(resp.text)


request_url = (
    "https://fmap.citizensfla.com/fmap/lead-agent/get-lead-agent-map-critieria"
)

headers = get_headers(request_url)

payload = json.dumps(
    {
        "numberOfRecordsPerPage": 50,
        "requestAll": False,
        "pageToShow": 1,
        "includeSortBy": False,
        "sortBy": None,
        "descending": True,
        "leadAgentMapId": None,
        "leadId": None,
        "agentId": 18653,
        "source": None,
        "quoteStatus": "TAGGED",
        # "quoteStatus": "NOT_TAGGED",
        "quoteStatuses": [],
        "quoteStatusesCritieria": "N",
        "emailSent": None,
        "addressId": None,
        "contactId": None,
        "county": None,
        "zip": None,
        "createdAt": None,
        "addressLine1": None,
        "constructionType": None,
        "propertyType": None,
        "ascending": True,
        "endingRecordNumber": 50,
        "startingRecordNumber": 1,
    }
)


resp = get_response(request_url, "POST", headers=headers, payload=payload)
# pp.pprint(resp.json())


respJson = resp.json()
for idx, result in enumerate(respJson["resultList"]):
    serial_no = idx
    try:
        prop_addr = result["leadAddressLabel"]
        prop_addr = str(prop_addr).upper()
    except:
        prop_addr = ""
    try:
        fname = result["leadRequest"]["firstName"]
        lname = result["leadRequest"]["lastName"]
        name = lname + ", " + fname

        phone = result["leadContact"]["cellPhoneNumber"]
        phone = str(phone)
        phone = "(" + phone[0:3] + ")" + phone[3:6] + "-" + phone[6:]

        email = result["leadContact"]["email"]

        consumer = name + " Home: " + phone + " Cell: <none&gt Email: " + email
    except:
        consumer = ""
    try:
        need_by = result["needByDateLabel"]
        need_by_parts = str(need_by).split()
        need_by_parts.pop(-2)
        need_by = " ".join(need_by_parts)
        need_by = datetime.datetime.strptime(need_by, "%c").strftime("%m-%d-%Y")
    except:
        need_by = ""
        traceback.print_exc()
    try:
        amount = result["amountLabel"]
        amount = int(float(amount))
        amount = "{:,}".format(amount)
    except:
        print(amount)
        traceback.print_exc()
        amount = ""
    try:
        prop_type = result["propertyTypeLabel"]
    except:
        prop_type = ""
    try:
        year = result["yearBuiltLabel"]
    except:
        year = ""
    try:
        construction = result["constructionTypeLabel"]
    except:
        construction = ""

    val = (
        serial_no,
        prop_addr,
        consumer,
        need_by,
        amount,
        prop_type,
        year,
        construction,
    )

    print(val)
    res = insert_data(val)
    if res:
        try:
            tag_lead(
                headers, result["leadAgentMapId"], result["leadId"], result["agentId"]
            )
        except:
            pass


driver.quit()
