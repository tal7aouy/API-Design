# BAD EXAMPLE: Do NOT copy this style.

from fastapi import FastAPI, Request
import sqlite3

app = FastAPI()
conn = sqlite3.connect('blog.db')


@app.post('/createPost')
async def create_post(request: Request):
    data = await request.json()
    # No validation, SQL injection risk
    conn.execute(
        f"INSERT INTO posts (title, body, user_id) VALUES ('{data['title']}', '{data['body']}', {data['user_id']})"
    )
    conn.commit()
    return {'status': 'ok'}  # no id, no status code handling


@app.get('/getPosts')
async def get_posts():
    cur = conn.cursor()
    cur.execute('SELECT * FROM posts')
    return cur.fetchall()  # raw tuples
