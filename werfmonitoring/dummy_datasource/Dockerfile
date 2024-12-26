FROM python:3.11-slim

WORKDIR /app

RUN pip install numpy paho-mqtt

COPY generator_taak.py /app

CMD ["python", "generator_taak.py"]
