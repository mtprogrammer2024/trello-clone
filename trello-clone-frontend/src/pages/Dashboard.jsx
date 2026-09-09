import { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import api from '../api/axios';
import { useAuth } from '../context/AuthContext';

function Dashboard() {
  const [workspaces, setWorkspaces] = useState([]);
  const [newWorkspaceName, setNewWorkspaceName] = useState('');
  const [loading, setLoading] = useState(true);

  const { user, logout } = useAuth();

  useEffect(() => {
    loadWorkspaces();
  }, []);

  async function loadWorkspaces() {
    setLoading(true);
    const res = await api.get('/workspaces');
    setWorkspaces(res.data);
    setLoading(false);
  }

  async function handleCreateWorkspace(e) {
    e.preventDefault();
    if (!newWorkspaceName.trim()) return;

    await api.post('/workspaces', { name: newWorkspaceName });
    setNewWorkspaceName('');
    loadWorkspaces();
  }

  return (
    <div className="min-h-screen bg-gray-100">
      <header className="bg-white shadow px-6 py-4 flex justify-between items-center">
        <h1 className="text-xl font-bold">Trello Clone</h1>
        <div className="flex items-center gap-4">
          <span className="text-sm text-gray-600">{user?.name}</span>
          <button
            onClick={logout}
            className="text-sm text-red-600 hover:underline"
          >
            خروج
          </button>
        </div>
      </header>

      <main className="max-w-4xl mx-auto p-6">
        <form onSubmit={handleCreateWorkspace} className="flex gap-2 mb-6">
          <input
            type="text"
            value={newWorkspaceName}
            onChange={(e) => setNewWorkspaceName(e.target.value)}
            placeholder="نام فضای کاری جدید..."
            className="flex-1 border rounded px-3 py-2"
          />
          <button
            type="submit"
            className="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
          >
            ساخت
          </button>
        </form>

        {loading ? (
          <p>در حال بارگذاری...</p>
        ) : workspaces.length === 0 ? (
          <p className="text-gray-500">هنوز فضای کاری‌ای نساختی.</p>
        ) : (
          <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
            {workspaces.map((workspace) => (
              <Link
                key={workspace.id}
                to={`/workspaces/${workspace.id}`}
                className="bg-white p-4 rounded-lg shadow hover:shadow-md transition-shadow"
              >
                <h2 className="font-bold">{workspace.name}</h2>
              </Link>
            ))}
          </div>
        )}
      </main>
    </div>
  );
}

export default Dashboard;