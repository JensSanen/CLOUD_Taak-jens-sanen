FROM python:3.10-slim

WORKDIR /app

COPY ./app /app

RUN pip install --no-cache-dir -r requirements.txt

EXPOSE 50051

USER root

ENTRYPOINT ["python", "server.py"]
