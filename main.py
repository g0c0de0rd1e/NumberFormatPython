import os
import re
import sys
import json
from fast_bitrix24 import Bitrix
from fastapi import FastAPI, HTTPException
from pydantic import BaseModel
from dotenv import load_dotenv
import uvicorn

# Загружаем переменные окружения
load_dotenv()

bitrix_token = os.getenv('BITRIX_TOKEN')
bitrix = Bitrix(bitrix_token)

def format_number(phone_number):
    return re.sub(r'\D', '', phone_number)

class BitrixEvent(BaseModel):
    data: dict

app = FastAPI()

@app.post("/format_numbers")
def format_numbers(event: BitrixEvent):
    contact_id = event.data['FIELDS']['ID']
    contact = bitrix.get_by_id('crm.contact.get', contact_id)
    phone_number = contact['PHONE'][0]['VALUE']
    formatted_phone = format_number(phone_number)
    
    bitrix.call('crm.contact.update', {
        'id': contact_id,
        'fields': {
            'PHONE': [{'VALUE': formatted_phone, 'VALUE_TYPE': 'WORK'}]
        }
    })
    
    return {"message": "Форматирование номеров завершено!"}

if __name__ == '__main__':
    if len(sys.argv) > 1:
        event_data = json.loads(sys.argv[1])
        format_numbers(BitrixEvent(data=event_data))
    else:
        uvicorn.run(app, host='0.0.0.0', port=8000)
