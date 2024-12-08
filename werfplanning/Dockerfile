FROM python:3.10

WORKDIR /app

COPY ./app /app

EXPOSE 5000

USER root

RUN pip install --no-cache-dir -r requirements.txt

ENTRYPOINT ["python", "app.py"]
