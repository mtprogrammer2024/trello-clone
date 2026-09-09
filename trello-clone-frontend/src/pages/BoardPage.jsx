import { useState, useEffect } from 'react';
import { useParams, Link } from 'react-router-dom';
import api from '../api/axios';

function BoardPage() {
  const { boardId } = useParams();
  const [board, setBoard] = useState(null);
  const [loading, setLoading] = useState(true);
  const [newListTitle, setNewListTitle] = useState('');

  useEffect(() => {
    loadBoard();
  }, [boardId]);

  async function loadBoard() {
    setLoading(true);
    const res = await api.get(`/boards/${boardId}`);
    setBoard(res.data);
    setLoading(false);
  }

  async function handleCreateList(e) {
    e.preventDefault();
    if (!newListTitle.trim()) return;

    await api.post(`/boards/${boardId}/lists`, { title: newListTitle });
    setNewListTitle('');
    loadBoard();
  }

  async function handleCreateCard(listId, title) {
    if (!title.trim()) return;
    await api.post(`/lists/${listId}/cards`, { title });
    loadBoard();
  }

  if (loading) return <p className="p-6">در حال بارگذاری...</p>;
  if (!board) return <p className="p-6">بورد پیدا نشد.</p>;

  return (
    <div
      className="min-h-screen"
      style={{ backgroundColor: board.background_color }}
    >
      <header className="p-4">
        <Link to="/" className="text-sm text-white hover:underline">
          ← بازگشت
        </Link>
        <h1 className="text-xl font-bold text-white mt-1">{board.title}</h1>
      </header>

      <main className="flex gap-4 p-4 overflow-x-auto">
        {board.lists?.map((list) => (
          <ListColumn
            key={list.id}
            list={list}
            onAddCard={handleCreateCard}
          />
        ))}

        <form
          onSubmit={handleCreateList}
          className="bg-white/80 rounded-lg p-3 w-64 shrink-0 h-fit"
        >
          <input
            type="text"
            value={newListTitle}
            onChange={(e) => setNewListTitle(e.target.value)}
            placeholder="+ افزودن لیست جدید"
            className="w-full border rounded px-2 py-1 text-sm"
          />
        </form>
      </main>
    </div>
  );
}

function ListColumn({ list, onAddCard }) {
  const [newCardTitle, setNewCardTitle] = useState('');

  async function handleSubmit(e) {
    e.preventDefault();
    await onAddCard(list.id, newCardTitle);
    setNewCardTitle('');
  }

  return (
    <div className="bg-gray-100 rounded-lg p-3 w-64 shrink-0">
      <h2 className="font-bold mb-3 text-sm">{list.title}</h2>

      <div className="flex flex-col gap-2 mb-3">
        {list.cards?.map((card) => (
          <div
            key={card.id}
            className="bg-white rounded p-2 text-sm shadow-sm"
          >
            {card.title}
          </div>
        ))}
      </div>

      <form onSubmit={handleSubmit}>
        <input
          type="text"
          value={newCardTitle}
          onChange={(e) => setNewCardTitle(e.target.value)}
          placeholder="+ افزودن کارت"
          className="w-full border rounded px-2 py-1 text-sm"
        />
      </form>
    </div>
  );
}

export default BoardPage;