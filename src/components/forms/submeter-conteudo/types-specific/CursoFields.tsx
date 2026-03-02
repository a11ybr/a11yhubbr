import { FormState } from "../types";

type Props = {
  form: FormState;
  onChange: (field: keyof FormState, value: any) => void;
};

export function CursoFields({ form, onChange }: Props) {
  return (
    <fieldset className="space-y-4">
      <legend className="block text-lg font-medium">Destaques do Curso ou Material</legend>
      <div className="space-y-3">
        <span className="block text-sm font-medium">
          Modalidade
          <span aria-hidden="true" className="text-destructive">*</span>
        </span>

        <div className="flex flex-row gap-2">
          {[
            { value: "online", label: "Online" },
            { value: "presencial", label: "Presencial" },
            { value: "hibrido", label: "Híbrido" },
          ].map((option) => (
            <div className="flex flex-row pl-2 text-sm gap-8">
              <label
                key={option.value}
                className="flex items-center gap-2 cursor-pointer"
              >
                <input
                  type="radio"
                  name="modalidade"
                  value={option.value}
                  checked={form.modalidade === option.value}
                  onChange={(e) =>
                    onChange("modalidade", e.target.value)
                  }
                  required
                />
                <span>{option.label}</span>
              </label>
            </div>
          ))}
        </div>
      </div>
      <div className="space-y-3">
        <span className="block text-sm font-medium">
          Preço
          <span aria-hidden="true" className="text-destructive">*</span>
        </span>

        <div className="flex flex-row gap-2">
          {[
            { value: "gratuito", label: "Gratuito" },
            { value: "pago", label: "Pago" },
          ].map((option) => (
            <div className="flex flex-row pl-2 text-sm gap-8">
              <label
                key={option.value}
                className="flex items-center gap-2 cursor-pointer"
              >
                <input
                  type="radio"
                  name="gratuitoPago"
                  value={option.value}
                  checked={form.gratuitoPago === option.value}
                  onChange={(e) =>
                    onChange("gratuitoPago", e.target.value)
                  }
                  required
                />
                <span>{option.label}</span>
              </label>
            </div>
          ))}
        </div>
      </div>
    </fieldset>
  );
}