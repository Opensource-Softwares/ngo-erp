from fastapi import FastAPI

app = FastAPI()

# 2. Define a sample route
@app.get("/")
def read_root():
    return {"message": "Hello, World!"}
