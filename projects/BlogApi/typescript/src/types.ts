export interface PostBase {
  title: string;
  body: string;
}

export interface PostCreate extends PostBase {
  authorId: string;
}

export interface Post extends PostBase {
  id: string;
  authorId: string;
  createdAt: string;
}
