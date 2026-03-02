import { Link } from "react-router-dom";
import { CONTENT_TYPES } from "@/constants/contentTypes";

export default function Conteudo() {
  return (
    <main className="container-site py-16">
      <header className="mb-12 max-w-2xl">
        <h1 className="text-3xl font-bold tracking-tight mb-4">
          Conteúdo
        </h1>
        <p className="text-muted-foreground">
          Explore recursos organizados por tipo. Cada categoria reúne iniciativas,
          materiais e referências relacionadas à acessibilidade digital.
        </p>
      </header>

      <section
        aria-labelledby="categorias-titulo"
        className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
      >
        <h2 id="categorias-titulo" className="sr-only">
          Categorias de conteúdo
        </h2>

        {CONTENT_TYPES.map((cat) => {
          const Icon = cat.icon;

          return (
            <Link
              key={cat.id}
              to={`/categoria/${cat.id}`}
              className="card-base p-6 no-underline hover:shadow-card-hover transition-all group focus:outline-none focus:ring-2 focus:ring-primary rounded-lg"
            >
              <div className="flex gap-4">
                <div className="w-32 h-16 flex items-center justify-center rounded-lg bg-primary/10 group-hover:bg-primary/20 transition-colors">
                  <Icon
                    size={22}
                    className="text-primary"
                    aria-hidden="true"
                  />
                </div>

                <div>
                  <h3 className="font-semibold text-lg">
                    {cat.title}
                  </h3>
                  <p className="text-sm text-muted-foreground">
                    {cat.description}
                  </p>
                </div>
              </div>
            </Link>
          );
        })}
      </section>
    </main>
  );
}