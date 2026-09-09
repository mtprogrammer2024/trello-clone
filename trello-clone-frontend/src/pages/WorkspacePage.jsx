import { useState, useEffect } from 'react';
import { useParams, Link } from 'react-router-dom';
import api from '../api/axios';

function WorkspacePage() {
  const { workspaceId } = useParams();
  const [workspace, setWorkspace] = useState(null);
  const [newBoardTitle, setNewBoardTitle] = useState('');
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    loadWorkspace();
  }, [workspaceId]);

  async function loadWorkspace() {
    setLoading(true);
    const res = await api.get(`/workspaces/${workspaceId}`);
    setWorkspace(res.data);
    setLoading(false);
  }

  async function handleCreateBoard(e) {
    e.preventDefault();
    if (!newBoardTitle.trim()) return;

    await api.post(`/workspaces/${workspaceId}/boards`, {
      title: newBoardTitle,
    });
    setNewBoardTitle('');
    loadWorkspace();
  }

  if (loading) return <p className="p-6">در حال بارگذاری...</p>;
  if (!workspace) return <p className="p-6">فضای کاری پیدا نشد.</p>;

  return (
    <div className="min-h-screen bg-gray-100">
      <header className="bg-white shadow px-6 py-4">
        <Link to="/" className="text-sm text-blue-600 hover:underline">
          ← بازگشت به داشبورد
        </Link>
        <h1 className="text-xl font-bold mt-2">{workspace.name}</h1>
      </header>

      <main className="max-w-4xl mx-auto p-6">
        <form onSubmit={handleCreateBoard} className="flex gap-2 mb-6">
          <input
            type="text"
            value={newBoardTitle}
            onChange={(e) => setNewBoardTitle(e.target.value)}
            placeholder="نام بورد جدید..."
            className="flex-1 border rounded px-3 py-2"
          />
          <button
            type="submit"
            className="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
          >
            ساخت
          </button>
        </form>

        {workspace.boards?.length === 0 ? (
          <p className="text-gray-500">هنوز بوردی نساختی.</p>
        ) : (
          <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
            {workspace.boards?.map((board) => (
              <Link
                key={board.id}
                to={`/boards/${board.id}`}
                className="p-6 rounded-lg shadow hover:shadow-md transition-shadow text-white font-bold"
                style={{ backgroundColor: board.background_color }}
              >
                {board.title}
              </Link>
            ))}
          </div>
        )}
      </main>
    </div>
  );
}

export default WorkspacePage;