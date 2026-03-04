import { useEffect, useState } from "react";
import { ContentCard } from "@/components/cards/ContentCard";
import { supabase, isSupabaseConfigured } from "@/lib/supabase";

interface Props {
  categoria: string;
}

export default function ListaConteudo({ categoria }: Props) {
  const [dados, setDados] = useState<any[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchData = async () => {
      if (!isSupabaseConfigured || !supabase) {
        setDados([]);
        setLoading(false);
        return;
      }

      setLoading(true);

      const { data, error } = await supabase
        .from("contents")
        .select("*")
        .eq("type", categoria)
        .eq("status", "approved");

      if (!error) setDados(data || []);

      setLoading(false);
    };

    fetchData();
  }, [categoria]);

  if (loading) return <p>Carregando...</p>;

  if (!dados.length) return <p>Nenhum conteúdo encontrado.</p>;

  return (
    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
      {dados.map((item) => (
        <ContentCard
          key={item.id}
          title={item.title}
          excerpt={item.description}
          type={item.type === "tutorial" || item.type === "projeto" ? item.type : "artigo"}
          tags={Array.isArray(item.metadata?.publicoAlvo) ? item.metadata.publicoAlvo : []}
          href={item.url}
          author={item.author_name}
          date={item.created_at ? new Date(item.created_at).toLocaleDateString("pt-BR") : "Sem data"}
        />
      ))}
    </div>
  );
}