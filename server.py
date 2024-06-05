#!/usr/bin/env python
# -*- coding: utf-8 -*-

import os
from flask import Flask, render_template, request
import requests
import datetime 
import re
from random import randrange

# Support for gomix's 'front-end' and 'back-end' UI.
app = Flask(__name__, static_folder='public', template_folder='views')

product_list = [
  {
    "name": "wfTuna",
    "link": "https://amzn.in/d/cvrBQ4R",
  },
  {
    "name": "wfChkn",
    "link": "https://amzn.in/d/7QiKuiT",
  },
  {
    "name": "dfMeatUp",
    "link": "https://amzn.in/d/4KYd9nW",
  },
]

def scrape_data(url, filename):
    url = "https://hypalgesic-seawater.000webhostapp.com/proxy.php?url=" + url
    response = requests.get(url)
    data = response.text
    
    location1 = data.find("One-time purchase")
    location2 = data.find("₹", location1)
    if location1 < 0 or location2 < 0:
      return "Pattern not found"
    val = re.findall("\d+\.\d+", data[location2+1:location2+10])
    data = val[0]
    
    with open("public/"+filename+".txt", "a") as fp:
      fp.write("%s,%s\n"%(datetime.datetime.now().isoformat(), val[0]))
    return filename+": "+data+"<br\>"

@app.route('/collect')
def collect():
    """Scrapes data."""
    
    data = ""
    prod_m = request.args.get("p")
    for p in product_list:
      if prod_m:
        if p["name"] == prod_m:
          data = data + scrape_data(p["link"], p["name"])
      else:
        data = data + scrape_data(p["link"], p["name"])
    return data
    
@app.route('/')
def homepage():
    """Shows homepage"""
    
    data = []
    
    for p in product_list:
      item = {
        "product": p["name"],
        "months": [],
      }
      data.append(item)
      month_dt = {}
      with open("public/"+p["name"]+".txt") as fp:
        for line in fp:
          line = line.strip()
          parts = line.split(",")
          
          if len(parts) < 2:
            continue
            
          time_h = datetime.datetime.fromisoformat(parts[0])
          month_s = time_h.strftime("%b")
          if month_s not in month_dt:
            month_dt[month_s] = []
          month_dt[month_s].append({
            "time": time_h.strftime("%m-%d %H:%M"),
            "price": parts[1],
          }) 
      for m in month_dt:
        item["months"].append({
          "month": m,
          "color_r": randrange(40, 255),
          "color_g": randrange(40, 255),
          "color_b": randrange(40, 255),
          "data": month_dt[m]
        })
          
    # Return the rendered template with embedded data
    return render_template('index.html', data=data)

if __name__ == '__main__':
    app.run()
