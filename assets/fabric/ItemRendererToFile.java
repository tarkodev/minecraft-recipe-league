package net.fabricmc.example;

import com.mojang.blaze3d.platform.GlStateManager;
import net.minecraft.client.MinecraftClient;
import net.minecraft.client.gl.Framebuffer;
import net.minecraft.client.util.math.MatrixStack;
import net.minecraft.item.Item;
import net.minecraft.item.ItemStack;
import net.minecraft.registry.Registries;
import org.lwjgl.opengl.GL11;

import javax.imageio.ImageIO;
import java.awt.image.BufferedImage;
import java.io.File;
import java.io.IOException;
import java.nio.ByteBuffer;

public class ItemRendererToFile {

    public static class SimpleFramebuffer extends Framebuffer {
        private final int width;
        private final int height;

        public SimpleFramebuffer(int width, int height, boolean useDepth) {
            super(useDepth);
            this.width = width;
            this.height = height;
            this.initFbo(width, height, MinecraftClient.IS_SYSTEM_MAC);
        }

        public int getWidth() {
            return this.width;
        }

        public int getHeight() {
            return this.height;
        }
    }

    private static final int IMAGE_SIZE = 256;
    private static final int IMAGE_WIDTH = IMAGE_SIZE;
    private static final int IMAGE_HEIGHT = IMAGE_SIZE;


    public static void render(Item item) {
        MinecraftClient client = MinecraftClient.getInstance();

        // Create custom framebuffer
        Framebuffer framebuffer = new SimpleFramebuffer(IMAGE_WIDTH, IMAGE_HEIGHT, true);
        framebuffer.beginWrite(true);
        render(client, item);
        BufferedImage image = readPixels(IMAGE_WIDTH, IMAGE_HEIGHT);
        framebuffer.endWrite();

        // Save image to file
        try {
            System.out.println(Registries.ITEM.getId(item).getPath());
            ImageIO.write(image, "png", new File(Registries.ITEM.getId(item).getPath() + ".png"));
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    private static void render(MinecraftClient client, Item item) {
        MatrixStack matrixStack = new MatrixStack();
        ItemStack diamondStack = new ItemStack(item);
        float scale = 32.F;
        matrixStack.push();

        GlStateManager._enableBlend();
        GlStateManager._blendFunc(GlStateManager.SrcFactor.SRC_ALPHA.value, GlStateManager.DstFactor.ONE_MINUS_SRC_ALPHA.value);

        matrixStack.scale(scale*1.89F, scale*1.05f, 1.1F);
        client.getItemRenderer().renderGuiItemIcon(matrixStack, diamondStack, 0, 0);
        matrixStack.pop();
    }

    private static BufferedImage readPixels(int width, int height) {
        ByteBuffer buffer = ByteBuffer.allocateDirect(width * height * 4);
        GlStateManager._readPixels(0, 0, width, height, GL11.GL_RGBA, GL11.GL_UNSIGNED_BYTE, buffer);

        BufferedImage image = new BufferedImage(width, height, BufferedImage.TYPE_INT_ARGB);
        for (int y = 0; y < height; y++) {
            for (int x = 0; x < width; x++) {
                int i = (x + (width * y)) * 4;
                int r = buffer.get(i) & 0xFF;
                int g = buffer.get(i + 1) & 0xFF;
                int b = buffer.get(i + 2) & 0xFF;
                int a = buffer.get(i + 3) & 0xFF;
                int argb = (a << 24) | (r << 16) | (g << 8) | b;
                image.setRGB(x, height - (y + 1), argb);
            }
        }

        return image;
    }
}