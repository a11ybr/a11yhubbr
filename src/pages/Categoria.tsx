import { useState, useEffect } from "react";
import { useParams } from "react-router-dom";
import { supabase, isSupabaseConfigured } from "@/lib/supabase";
import SidebarFiltros from "@/components/SidebarFiltros";

export default function Categoria() {
  const { slug } = useParams();

  const [filters, setFilters] = useState({
    search: "",
    sort: "recent",
    status: [] as string[],
    types: [] as string[]
  });

  const [items, setItems] = useState<any[]>([]);
  const [loading, setLoading] = useState(false);

  const fetchData = async () => {
    if (!isSupabaseConfigured || !supabase) {
      setItems([]);
      setLoading(false);
      return;
    }

    setLoading(true);

    let query = supabase.from("contents").select("*");

    if (slug && slug !== "todos") {
      query = query.eq("category_slug", slug);
    }

    if (filters.search) {
      query = query.ilike("title", `%${filters.search}%`);
    }

    if (filters.status.length > 0) {
      query = query.in("status", filters.status);
    }

    if (filters.types.length > 0) {
      query = query.in("type", filters.types);
    }

    if (filters.sort === "alphabetical") {
      query = query.order("title", { ascending: true });
    } else {
      query = query.order("created_at", { ascending: false });
    }

    const { data } = await query;

    setItems(data || []);
    setLoading(false);
  };

  useEffect(() => {
    fetchData();
  }, [filters, slug]);

  return (
    <main className="container-site py-16">
      <div className="grid grid-cols-1 lg:grid-cols-[280px_1fr] gap-12">

        <SidebarFiltros
          filters={filters}
          setFilters={setFilters}
        />

        <section>
          {loading && <p>Carregando...</p>}

          {!loading && items.length === 0 && (
            <p className="text-muted-foreground">
              Nenhum conteúdo encontrado.
            </p>
          )}

          <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
            {items.map((item) => (
              <div key={item.id} className="card-base p-6">
                <h2 className="font-semibold mb-2">
                  {item.title}
                </h2>
                <p className="text-sm text-muted-foreground">
                  {item.description}
                </p>
              </div>
            ))}
          </div>
        </section>
      </div>
    </main>
  );
}
